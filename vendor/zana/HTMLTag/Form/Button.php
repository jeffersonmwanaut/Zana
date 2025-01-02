<?php namespace Zana\HTMLTag\Form;

class Button extends HTMLTag {
    public function __construct($label) {
        parent::__construct('button'); // Set the tag to 'button'
        $this->addContent($label); // Add the label as content
    }

    public function render() {
        // Render the button using the HTMLTag's render method
        return parent::render();
    }
}