<?php namespace Zana\Form;

class RuleValidator {
    public function validate($ruleKey, $value, $rule) {
        switch ($ruleKey) {
            case 'required':
                return !empty($value);
            case 'email':
                if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $emailParts = explode('@', $value);
                    $domain = $emailParts[1];
                    return checkdnsrr($domain . '.', 'MX');
                }
                return false;
            case 'min':
                $minValue = (int) explode(':', $rule)[1];
                return $value >= $minValue;
            case 'max':
                $maxValue = (int) explode(':', $rule)[1];
                return $value <= $maxValue;
            case 'minLength':
                $length = (int) explode(':', $rule)[1];
                return strlen($value) >= $length;
            case 'maxLength':
                $length = (int) explode(':', $rule)[1];
                return strlen($value) <= $length;
            default:
                return true;
        }
    }
}