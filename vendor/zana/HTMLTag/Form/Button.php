<?php namespace Zana\HTMLTag\Form;

class Button extends HTMLTag {
    public function __construct($type = 'submit', string $text = 'submit') {
        parent::__construct('button');
        $this->setType($type);
        $this->addContent($content);
    }

    public function setType($type) {
        return $this->setAttribute('type', $type);
    }

    public function render() {
        // Render the button using the HTMLTag's render method
        return parent::render();
    }
}