<?php namespace Zana\ABAC;

class Rule {
    private array $subject;
    private array $action;
    private array $resource;
    private array $environment; // Optional
    private array $confidentialityLevel; // Optional

    public function __construct($subject, $action, $resource, $environment = [], $confidentialityLevel = []) {
        $this->subject = (array) $subject; // Ensure it's an array
        $this->action = (array) $action;   // Ensure it's an array
        $this->resource = (array) $resource; // Ensure it's an array
        $this->environment = (array) $environment; // Initialize as array, default to empty
        $this->confidentialityLevel = (array) $confidentialityLevel; // Initialize as array, default to empty
    }

    public function getSubject(): array {
        return $this->subject;
    }

    public function getAction(): array {
        return $this->action;
    }

    public function getResource(): array {
        return $this->resource;
    }

    public function getEnvironment(): array {
        return $this->environment;
    }

    public function getConfidentialityLevel(): array {
        return $this->confidentialityLevel;
    }
}