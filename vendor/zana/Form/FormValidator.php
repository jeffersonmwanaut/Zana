<?php namespace Zana\Form;

class FormValidator {
    private $errors = [];
    private $data = [];
    private $customErrorMessages = [];
    private $ruleValidator;

    public function __construct($data) {
        $this->data = $data;
        $this->ruleValidator = new RuleValidator();
    }

    public function validate($rules, $customErrorMessages = []) {
        $this->customErrorMessages = $customErrorMessages;
        foreach ($rules as $field => $rule) {
            if(!isset($this->data[$field])) continue;
            $value = $this->data[$field];
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

    private function getErrorMessage($ruleKey, $field, $rule) {
        if (isset($this->customErrorMessages[$ruleKey])) {
            return str_replace('{field}', $field, $this->customErrorMessages[$ruleKey]);
        }

        switch ($ruleKey) {
            case 'required':
                return "The $field field is required.";
            case 'email':
                return "Invalid email address for $field.";
            case 'minLength':
                $length = (int) explode(':', $rule)[1];
                return "Minimum length for $field is $length characters.";
            case 'maxLength':
                $length = (int) explode(':', $rule)[1];
                return "Maximum length for $field is $length characters.";
            default:
                return "Invalid input for $field.";
        }
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }
}