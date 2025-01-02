<?php namespace Zana\HTMLTag\Table;

class TableFoot extends HTMLTag {
    public function __construct() {
        parent::__construct('tfoot'); // Set the tag to 'tfoot'
    }

    public function addRow(TableRow $row) {
        $this->addContent($row);
        return $this; // Allow method chaining
    }
}