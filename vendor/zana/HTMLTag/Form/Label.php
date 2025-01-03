<?php namespace Zana\HTMLTag\Form;

use Zana\HTMLTag\HTMLtag;

class Label extends HTMLTag {
    public function __construct($for, $text) {
        parent::__construct('label');
        $this->setAttribute('for', $for);
        $this->setAttribute('class', 'form-label');
        $this->addContent($text);
    }
}