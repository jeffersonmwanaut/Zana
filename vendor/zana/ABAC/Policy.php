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
        $policies = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding JSON: " . json_last_error_msg());
        }

        return $policies;
    }

    /**
     * Check if the access matches any policy.
     * @param array $access
     * @return bool
     */
    private function isMatch(array $access): bool {
        $allowFound = false;
        foreach ($this->policies['policies'] as $policy) {
            // Check the effect of the policy
            if ($this->policyMatchesAccess($policy, $access)) {
                if ($policy['effect'] === 'deny') {
                    return false; // Deny access immediately if a deny rule is found
                }
                if ($policy['effect'] === 'allow') {
                    $allowFound = true; // Found an allow rule
                }
            }
        }
    
        return $allowFound; // Grant access if an allow rule is found and no deny rule was found
    }

    /**
     * Check if the policy matches the access.
     * @param array $policy
     * @param array $access
     * @return bool
     */
    private function policyMatchesAccess(array $policy, array $access): bool {
        foreach ($policy['rules'] as $rule) {
            if ($this->ruleMatchesAccess($rule, $access)) {
                return true; // If a match is found, return true
            }
        }
        return false; // If no match is found, return false
    }

    /**
     * Check if the rule matches the access.
     * @param array $rule
     * @param array $access
     * @return bool
     */
    private function ruleMatchesAccess(array $rule, array $access): bool {
        // Check if the subject matches
        if (isset($rule['subject']) && !$this->matchesAny($access['subject'], $rule['subject'])) {
            return false; // Subject does not match
        }
        // Check if the action matches
        if (isset($rule['action']) && !$this->matchesAny($access['action'], $rule['action'])) {
            return false; // Action does not match
        }
        // Check if the resource matches
        if (isset($rule['resource']) && !$this->matchesAny($access['resource'], $rule['resource'])) {
            return false; // Resource does not match
        }
        // Check if the environment matches
        if (isset($rule['environment']) && !$this->matchesAny($access['environment'], $rule['environment'])) {
            return false; // Environment does not match
        }
        return true; // All conditions matched
    }

    /**
     * Check access against the policies.
     * @param array $access
     * @return bool
     */
    public function checkAccess(array $access): bool {
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
                $env = $rule['environment'] ?? 'default'; // Use 'default' if no environment is specified
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
     * @param array $rule1
     * @param array $rule2
     * @return bool
     */
    private function isOverlapping(array $rule1, array $rule2): bool {
        return $this->conditionsOverlap($rule1, $rule2) && $rule1['effect'] !== $rule2['effect'];
    }

    /**
     * Check if two rules are redundant.
     * @param array $rule1
     * @param array $rule2
     * @return bool
     */
    private function isRedundant(array $rule1, array $rule2): bool {
        return $this->conditionsMatch($rule1, $rule2) && $rule1['effect'] === $rule2['effect'];
    }

    /**
     * Check if one rule is shadowed by another.
     * @param array $rule1
     * @param array $rule2
     * @return bool
     */
    private function isShadowed(array $rule1, array $rule2): bool {
        return $this->conditionsMatch($rule1, $rule2) && $rule1['effect'] !== $rule2['effect'];
    }

    /**
     * Check if two rules have the same conditions.
     * @param array $rule1
     * @param array $rule2
     * @return bool
     */
    private function conditionsMatch(array $rule1, array $rule2): bool {
        return $this->matchesAny($rule1['subject'], $rule2['subject']) &&
               $this->matchesAny($rule1['action'], $rule2['action']) &&
               $this->matchesAny($rule1['resource'], $rule2['resource']) &&
               $this->matchesAny($rule1['environment'], $rule2['environment']);
    }

    /**
     * Check if two rules overlap in terms of subject, action, and resource.
     * @param array $rule1
     * @param array $rule2
     * @return bool
     */
    private function conditionsOverlap(array $rule1, array $rule2): bool {
        return ($this->matchesAny($rule1['subject'], $rule2['subject']) &&
                $this->matchesAny($rule1['action'], $rule2['action']) &&
                $this->matchesAny($rule1['resource'], $rule2['resource']));
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