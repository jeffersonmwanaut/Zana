<?php namespace Zana\Form;

class RuleValidator {
    private $formData;

    public function __construct($formData) {
        $this->formData = $formData;
    }

    public function validate($ruleKey, $value, $rule) {
        switch ($ruleKey) {
            case 'id':
                return preg_match('/^[a-zA-Z0-9_]+$/', $value);
            case 'required':
                return !empty($value);
            case 'email':
                if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $emailParts = explode('@', $value);
                    $domain = $emailParts[1];
                    return checkdnsrr($domain . '.', 'MX');
                }
                return false;
            case 'password':
                $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';
                return preg_match($pattern, $value);
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
            case 'equalTo':
                $compareToField = explode(':', $rule)[1];
                return $value == $this->formData[$compareToField];
            case 'lessThan':
                $compareToField = explode(':', $rule)[1];
                return $value < $this->formData[$compareToField];
            case 'lessThanOrEqualTo':
                $compareToField = explode(':', $rule)[1];
                return $value <= $this->formData[$compareToField];
            case 'greaterThan':
                $compareToField = explode(':', $rule)[1];
                return $value > $this->formData[$compareToField];
            case 'greaterThanOrEqualTo':
                $compareToField = explode(':', $rule)[1];
                return $value >= $this->formData[$compareToField];
            default:
                return true;
        }
    }
}