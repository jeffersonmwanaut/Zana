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
        foreach ($this->policies['policies'] as $policy) {
            if ($this->policyMatchesAccess($policy, $access)) {
                return true; // Access granted if any policy matches
            }
        }
        return false; // No matching policy found
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
        foreach ($rule as $key => $value) {
            if (!array_key_exists($key, $access) || $access[$key] !== $value) {
                return false; // If the rule condition does not match, return false
            }
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
}