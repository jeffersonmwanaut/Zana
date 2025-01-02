<?php namespace Zana\HTMLTag\Form;

class Form extends HTMLTag {
    public function __construct($action = '', $method = 'POST') {
        parent::__construct('form'); // Set the tag to 'form'
        $this->setAction($action); // Set the form action
        $this->setMethod($method); // Set the form method
    }

    public function setAction($action) {
        return $this->setAttribute('action', $action);
    }

    public function setMethod($method) {
        return $this->setAttribute('method', $method);
    }

    public function addField(Input $input) {
        $this->addContent($input);
        return $this; // Allow method chaining
    }

    public function addButton(Button $button) {
        $this->addContent($button);
        return $this; // Allow method chaining
    }

    public function clearFields() {
        // Filter out all Input objects from the content
        $this->content = array_filter($this->content, function($item) {
            return !($item instanceof Input); // Keep only non-Input items
        });
        return $this; // Allow method chaining
    }

    public function clearButtons() {
        // Filter out all Button objects from the content
        $this->content = array_filter($this->content, function($item) {
            return !($item instanceof Button); // Keep only non-Button items
        });
        return $this; // Allow method chaining
    }

    public function clearControls() {
        return $this->clearContent();
    }
}