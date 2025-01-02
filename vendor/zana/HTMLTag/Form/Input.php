<?php namespace Zana\HTMLTag\Form;

class Input extends HTMLTag {
    protected $label;

    public function __construct($id, $name, $type = 'text', Label $label = null) {
        parent::__construct('input'); // Set the tag to 'input'
        $this->setType($type);
        $this->setId($id);
        $this->setName($name);
        $this->label = $label; // Store the Label object
    }

    public function setType($type) {
        return $this->setAttribute('type', $type);
    }

    public function setId($id) {
        return $this->setAttribute('id', $id);
    }

    public function setName($name) {
        return $this->setAttribute('name', $name);
    }

    public function render() {
        // Render the label if it exists
        $labelHtml = $this->label ? $this->label->render() : '';

        // Render the input element using the HTMLTag's render method
        $inputHtml = parent::render(); // Use the parent render method for the input

        return $labelHtml . $inputHtml; // Combine label and input HTML
    }
}