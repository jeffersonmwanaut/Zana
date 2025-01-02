<?php namespace Zana\HTMLTag\Form;

class Option extends HTMLTag {
    public function __construct($value, $text, $isSelected = false) {
        parent::__construct('option'); // Set the tag to 'option'
        $this->setAttribute('value', $value);
        if ($isSelected) {
            $this->setAttribute('selected', 'selected');
        }
        $this->addContent($text);
    }
}