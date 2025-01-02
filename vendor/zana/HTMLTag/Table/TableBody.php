<?php namespace Zana\HTMLTag\Table;

class TableBody extends HTMLTag {
    public function __construct() {
        parent::__construct('tbody'); // Set the tag to 'tbody'
    }

    public function addRow(TableRow $row) {
        $this->addContent($row);
        return $this; // Allow method chaining
    }
}