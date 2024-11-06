<?php namespace Zana\Form;

class FormValidator {
    private $errors = [];
    private $formData = [];
    private $customErrorMessages = [];
    private $ruleValidator;

    public function __construct($formData) {
        $this->formData = $formData;
        $this->ruleValidator = new RuleValidator($formData);
    }

    public function validate($rules, $customErrorMessages = []) {
        $this->customErrorMessages = $customErrorMessages;
        foreach ($rules as $field => $rule) {
            if(!isset($this->formData[$field])) continue;
            $value = $this->formData[$field];
            $ruleParts = explode('|', $rule);

            // Prioritize rules
            $prioritizedRules = $this->prioritizeRules($ruleParts);
            foreach ($prioritizedRules as $rulePart) {
                if (!$this->ruleValidator->validate(explode(':', $rulePart)[0], $value, $rulePart)) {
                    $this->addError($field, $rulePart);
                }
            }
        }

        return !$this->hasErrors();
    }

    private function prioritizeRules($ruleParts) {
        $prioritizedRules = [];
        $requiredRule = null;

        foreach ($ruleParts as $rulePart) {
            $ruleKey = explode(':', $rulePart)[0];
            if ($ruleKey === 'required') {
                $requiredRule = $rulePart;
            } else {
                $prioritizedRules[] = $rulePart;
            }
        }

        if ($requiredRule) {
            array_unshift($prioritizedRules, $requiredRule);
        }

        return $prioritizedRules;
    }

    private function addError($field, $rule) {
        $ruleKey = explode(':', $rule)[0];
        $errorMessage = $this->getErrorMessage($ruleKey, $field, $rule);
        $this->errors[$field][] = $errorMessage;
    }

    public function getErrorMessage($ruleKey, $field, $rule) {
        if (isset($this->customErrorMessages[$ruleKey])) {
            return str_replace('{field}', $field, $this->customErrorMessages[$ruleKey]);
        }
    
        switch ($ruleKey) {
            case 'id':
                return "The $field field can only have letters and numbers.";
            case 'required':
                return "The $field field is required.";
            case 'email':
                return "The $field field must be a valid email address.";
            case 'password':
                return "The $field field must be at least 8 characters long, contains at least one uppercase and one lowercase letter, one digit, and one special character.";
            case 'min':
                $minValue = (int) explode(':', $rule)[1];
                return "The $field field must be at least $minValue.";
            case 'max':
                $maxValue = (int) explode(':', $rule)[1];
                return "The $field field must be at most $maxValue.";
            case 'minLength':
                $length = (int) explode(':', $rule)[1];
                return "The $field field must be at least $length characters long.";
            case 'maxLength':
                $length = (int) explode(':', $rule)[1];
                return "The $field field must be at most $length characters long.";
            case 'equalTo':
                $compareToField = explode(':', $rule)[1];
                return "The $field field must be equal to the $compareToField field.";
            case 'lessThan':
                $compareToField = explode(':', $rule)[1];
                return "The $field field must be less than the $compareToField field.";
            case 'lessThanOrEqualTo':
                $compareToField = explode(':', $rule)[1];
                return "The $field field must be less than or equal to the $compareToField field.";
            case 'greaterThan':
                $compareToField = explode(':', $rule)[1];
                return "The $field field must be greater than the $compareToField field.";
            case 'greaterThanOrEqualTo':
                $compareToField = explode(':', $rule)[1];
                return "The $field field must be greater than or equal to the $compareToField field.";
            default:
                return "The $field field is invalid.";
        }
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}