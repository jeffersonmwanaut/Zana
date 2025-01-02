<?php namespace Zana\HTMLTag\Form;

class Label extends HTMLTag {
    public function __construct($for, $text) {
        parent::__construct('label'); // Set the tag to 'label'
        $this->setAttribute('for', $for); // Set the 'for' attribute
        $this->addContent($text); // Set the label text as content
    }
}