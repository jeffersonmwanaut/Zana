<?php namespace Zana\ABAC;

class Rule {
    private array $subject;
    private array $action;
    private array $resource;
    private array $environment;

    public function __construct($subject, $action, $resource, $environment) {
        $this->subject = (array) $subject; // Ensure it's an array
        $this->action = (array) $action;   // Ensure it's an array
        $this->resource = (array) $resource; // Ensure it's an array
        $this->environment = (array) $environment; // Ensure it's an array
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
}