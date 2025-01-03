<?php namespace Zana\HTMLTag\List;

use Zana\HTMLTag\HTMLtag;

class UnorderedList extends HTMLTag {
    public function __construct() {
        parent::__construct('ul'); // Set the tag to 'ul'
    }

    public function addItem(ListItem $listItem) {
        $this->addContent($listItem);
        return $this; // Allow method chaining
    }
}