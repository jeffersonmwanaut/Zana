<?php namespace Zana\ABAC;

class Policy {
    private $policies;

    public function __construct(string $policyConfigFilename) {
        $this->policies = $this->loadPolicies($policyConfigFilename);
    }

    public function getPolicies()
    {
        return $this->policies;
    }

    private function loadPolicies(string $policyConfigFilename) {
        // Read the JSON policy file
        $json = file_get_contents($policyConfigFilename);

        // Convert the JSON string to a PHP array
        $policies = json_decode($json, true);

        // Return the policies array
        return $policies;
    }

    private function isMatch($access) {
        // Initialize the default result as false
        $result = false;
    
        // Loop through the policies array
        foreach ($this->policies['policies'] as $policy) {
            // Check if the access matches the current policy
            if ($this->policyMatchesAccess($policy, $access)) {
                // If a match is found, check the policy effect
                if ($policy['effect'] !== 'allow') {
                    // If the policy effect is not allow, return false
                    return false;
                } else {
                    // If the policy effect is allow, update the default result as true
                    $result = true;
                }
            }
        }
    
        // If no match is found, return the default result
        return $result;
    }

    private function policyMatchesAccess($policy, $access) {
        // Loop through the rules array in the current policy
        foreach ($policy['rules'] as $rule) {
            // Check if the rule matches the access
            if ($this->ruleMatchesAccess($rule, $access)) {
                // If a match is found, return true
                return true;
            }
        }
    
        // If no match is found, return false
        return false;
    }

    private function ruleMatchesAccess($rule, $access) {
        // Initialize the default result as false
        $result = false;
        // Loop through the conditions in the current rule
        foreach ($rule as $key => $value) {
            // Check if the access has a corresponding property
            if (array_key_exists($key, $access)) {
                // If a match is found, check if the rule condition matches the access property
                if ($access[$key] !== $value) {
                    // If the rule condition does not match the access property, return false
                    return false;
                } else {
                    $r = $rule;
                    // If the rule condition matches the access property, update the default result as true
                    $result = true;
                }
            }
        }
    
        // If no match is found, return the default result
        return $result;
    }

    public function checkAccess($access) {
        return $this->isMatch($access);
    }
}