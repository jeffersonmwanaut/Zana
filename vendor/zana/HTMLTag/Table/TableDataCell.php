<?php namespace Zana\HTMLTag\Table;

use Zana\HTMLTag\HTMLtag;

class TableDataCell extends HTMLTag {
    public function __construct($content) {
        parent::__construct('td'); // Set the tag to 'td'
        $this->addContent($content); // Set the content of the data cell
    }
}