<?php namespace Zana\HTMLTag;

class Paragraph extends HTMLTag {
    public function __construct($content = '') {
        parent::__construct('p');
        $this->addContent($content);
    }
}