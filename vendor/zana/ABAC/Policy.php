<?php namespace Zana\ABAC;

class Policy {
    private array $policies;

    public function __construct(string $policyConfigFilename) {
        $this->policies = $this->loadPolicies($policyConfigFilename);
    }

    /**
     * Get the loaded policies.
     * @return array
     */
    public function getPolicies(): array {
        return $this->policies;
    }

    /**
     * Load policies from a JSON file.
     * @param string $policyConfigFilename
     * @return array
     * @throws Exception
     */
    private function loadPolicies(string $policyConfigFilename): array {
        if (!file_exists($policyConfigFilename)) {
            throw new Exception("Policy configuration file not found: " . $policyConfigFilename);
        }

        $json = file_get_contents($policyConfigFilename);
        $policyData = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        // Create a new array to hold the policies with Rule objects
        $policiesWithRules = [];

        foreach ($policyData['policies'] as $policy) {
            $rules = [];
            foreach ($policy['rules'] as $ruleData) {
                $rules[] = new Rule(
                    $ruleData['subject'],
                    $ruleData['action'],
                    $ruleData['resource'],
                    $ruleData['environment']
                );
            }
            // Add the policy with the new rules array
            $policiesWithRules[] = [
                'id' => $policy['id'],
                'effect' => $policy['effect'],
                'rules' => $rules
            ];
        }

        return ['policies' => $policiesWithRules];
    }

    /**
     * Check if the access matches any policy.
     * @param Rule $access
     * @return bool
     */
    private function isMatch(Rule $access): bool {
        $allowFound = false;
        foreach ($this->policies['policies'] as $policy) {
            foreach ($policy['rules'] as $rule) {
                if ($this->ruleMatchesAccess($rule, $access)) {
                    if ($policy['effect'] === 'deny') {
                        return false; // Deny access immediately if a deny rule is found
                    }
                    if ($policy['effect'] === 'allow') {
                        $allowFound = true; // Found an allow rule
                    }
                }
            }
        }

        return $allowFound; // Grant access if an allow rule is found and no deny rule was found
    }

    /**
     * Check if the rule matches the access.
     * @param Rule $rule
     * @param Rule $access
     * @return bool
     */
    private function ruleMatchesAccess(Rule $rule, Rule $access): bool {
        // Check if the subject matches
        if (!$this->matchesAny($access->getSubject(), $rule->getSubject())) {
            return false; // Subject does not match
        }
        // Check if the action matches
        if (!$this->matchesAny($access->getAction(), $rule->getAction())) {
            return false; // Action does not match
        }
        // Check if the resource matches
        if (!$this->matchesAny($access->getResource(), $rule->getResource())) {
            return false; // Resource does not match
        }
        // Check if the environment matches
        if (!$this->matchesAny($access->getEnvironment(), $rule->getEnvironment())) {
            return false; // Environment does not match
        }
        return true; // All conditions matched
    }

    /**
     * Check access against the policies.
     * @param Rule $access
     * @return bool
     */
    public function checkAccess(Rule $access): bool {
        return $this->isMatch($access);
    }

    /**
     * Detect conflicts in the loaded policies.
     * @return array
     */
    public function detectConflicts(): array {
        $conflicts = [];

        // Group rules by environment
        $rulesByEnvironment = [];
        foreach ($this->policies['policies'] as $policy) {
            foreach ($policy['rules'] as $rule) {
                $env = $rule->getEnvironment()[0] ?? 'default'; // Use the first environment if no specific one is specified
                $rulesByEnvironment[$env][] = $rule;
            }
        }

        // Check for conflicts within each environment
        foreach ($rulesByEnvironment as $environment => $rules) {
            $conflicts = array_merge($conflicts, $this->checkForConflictsInEnvironment($rules, $environment));
        }

        return $conflicts;
    }

    /**
     * Check for conflicts within a specific environment.
     * @param array $rules
     * @param string $environment
     * @return array
     */
    private function checkForConflictsInEnvironment(array $rules, string $environment): array {
        $conflicts = [];
        $ruleCount = count($rules);

        for ($i = 0; $i < $ruleCount; $i++) {
            for ($j = $i + 1; $j < $ruleCount; $j++) {
                if ($this->isOverlapping($rules[$i], $rules[$j])) {
                    $conflicts[] = [
                        'environment' => $environment,
                        'conflicting_rules' => [$rules[$i], $rules[$j]]
                    ];
                }
                if ($this->isRedundant($rules[$i], $rules[$j])) {
                    $conflicts[] = [
                        'environment' => $environment,
                        'redundant_rules' => [$rules[$i], $rules[$j]]
                    ];
                }
                if ($this->isShadowed($rules[$i], $rules[$j])) {
                    $conflicts[] = [
                        'environment' => $environment,
                        'shadowed_rule' => $rules[$i],
                        'shadowing_rule' => $rules[$j]
                    ];
                }
            }
        }

        return $conflicts;
    }

    /**
     * Determine if two rules are conflicting.
     * @param Rule $rule1
     * @param Rule $rule2
     * @return bool
     */
    private function isOverlapping(Rule $rule1, Rule $rule2): bool {
        return $this->conditionsOverlap($rule1, $rule2) && $rule1->getEffect() !== $rule2->getEffect();
    }

    /**
     * Check if two rules are redundant.
     * @param Rule $rule1
     * @param Rule $rule2
     * @return bool
     */
    private function isRedundant(Rule $rule1, Rule $rule2): bool {
        return $this->conditionsMatch($rule1, $rule2) && $rule1->getEffect() === $rule2->getEffect();
    }

    /**
     * Check if one rule is shadowed by another.
     * @param Rule $rule1
     * @param Rule $rule2
     * @return bool
     */
    private function isShadowed(Rule $rule1, Rule $rule2): bool {
        return $this->conditionsMatch($rule1, $rule2) && $rule1->getEffect() !== $rule2->getEffect();
    }

    /**
     * Check if two rules have the same conditions.
     * @param Rule $rule1
     * @param Rule $rule2
     * @return bool
     */
    private function conditionsMatch(Rule $rule1, Rule $rule2): bool {
        return $this->matchesAny($rule1->getSubject(), $rule2->getSubject()) &&
               $this->matchesAny($rule1->getAction(), $rule2->getAction()) &&
               $this->matchesAny($rule1->getResource(), $rule2->getResource()) &&
               $this->matchesAny($rule1->getEnvironment(), $rule2->getEnvironment());
    }

    /**
     * Check if two rules overlap in terms of subject, action, and resource.
     * @param Rule $rule1
     * @param Rule $rule2
     * @return bool
     */
    private function conditionsOverlap(Rule $rule1, Rule $rule2): bool {
        return ($this->matchesAny($rule1->getSubject(), $rule2->getSubject()) &&
                $this->matchesAny($rule1->getAction(), $rule2->getAction()) &&
                $this->matchesAny($rule1->getResource(), $rule2->getResource()));
    }

    /**
     * Check if one value matches any of the other.
     * @param mixed $value1
     * @param mixed $value2
     * @return bool
     */
    private function matchesAny($value1, $value2): bool {
        $value1Array = is_array($value1) ? $value1 : [$value1];
        $value2Array = is_array($value2) ? $value2 : [$value2];

        foreach ($value1Array as $v1) {
            foreach ($value2Array as $v2) {
                if ($v1 === $v2 || $v1 === '*' || $v2 === '*') {
                    return true;
                }
            }
        }
        return false;
    }
}