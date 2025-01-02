<?php namespace Zana\HTMLTag\Table;

class TableRow extends HTMLTag {
    public function __construct() {
        parent::__construct('tr'); // Set the tag to 'tr'
    }

    public function addCell(TableDataCell $cell) {
        $this->addContent($cell); // Add a TableDataCell to the row
        return $this; // Allow method chaining
    }
}