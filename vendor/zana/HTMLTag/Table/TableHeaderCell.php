<?php namespace Zana\HTMLTag\Table;

use Zana\HTMLTag\HTMLtag;

class TableHeaderCell extends HTMLTag {
    public function __construct($content) {
        parent::__construct('th'); // Set the tag to 'th'
        $this->addContent($content); // Set the content of the header cell
    }
}