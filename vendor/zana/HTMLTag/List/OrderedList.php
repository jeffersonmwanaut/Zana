<?php namespace Zana\HTMLTag\List;

class OrderedList extends HTMLTag {
    public function __construct() {
        parent::__construct('ol'); // Set the tag to 'ol'
    }

    public function addItem(ListItem $listItem) {
        $this->addContent($listItem);
        return $this; // Allow method chaining
    }
}