<?php namespace Zana\Form;

class FormValidator {
    private $errors = [];
    private $data = [];
  
    public function __construct($data) {
        $this->data = $data;
    }
  
    public function validate($rules) {
        foreach ($rules as $field => $rule) {
            if(!isset($this->data[$field])) continue;
            $value = $this->data[$field];
            $ruleParts = explode('|', $rule);
    
            foreach ($ruleParts as $rulePart) {
                if ($this->validateRule($field, $value, $rulePart) === false) {
                    $this->addError($field, $rulePart);
                }
            }
        }
    
        return !$this->hasErrors();
    }
  
    private function validateRule($field, $value, $rule) {
        $ruleKey = explode(':', $rule)[0];
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
  
    private function addError($field, $rule) {
        $this->errors[$field][] = $this->getErrorMessage($rule);
    }
  
    private function getErrorMessage($rule) {
        $ruleKey = explode(':', $rule)[0];
        switch ($ruleKey) {
            case 'required':
                return 'This field is required.';
            case 'email':
                return 'Invalid email address.';
            case 'minLength':
                $length = (int) explode(':', $rule)[1];
                return "Minimum length is $length characters.";
            case 'maxLength':
                $length = (int) explode(':', $rule)[1];
                return "Maximum length is $length characters.";
            default:
                return 'Invalid input.';
        }
    }
  
    public function hasErrors() {
        return !empty($this->errors);
    }
  
    public function getErrors() {
        return $this->errors;
    }
}