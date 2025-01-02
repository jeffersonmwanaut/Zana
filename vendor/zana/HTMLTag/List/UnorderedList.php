<?php namespace Zana\HTMLTag\List;

class UnorderedList extends HTMLTag {
    public function __construct() {
        parent::__construct('ul'); // Set the tag to 'ul'
    }

    public function addItem(ListItem $listItem) {
        $this->addContent($listItem);
        return $this; // Allow method chaining
    }
}