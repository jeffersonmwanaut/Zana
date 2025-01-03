<?php namespace Zana\HTMLTag\List;

use Zana\HTMLTag\HTMLtag;

class ListItem extends HTMLTag {
    public function __construct($content) {
        parent::__construct('li'); // Set the tag to 'li'
        $this->addContent($content); // Set the content of the list item
    }
}