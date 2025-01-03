<?php namespace Zana\HTMLTag;

use Zana\HTMLTag\HTMLtag;

class Paragraph extends HTMLTag {
    public function __construct($content = '') {
        parent::__construct('p');
        $this->addContent($content);
    }
}