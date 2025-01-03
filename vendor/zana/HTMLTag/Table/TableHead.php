<?php namespace Zana\HTMLTag\Table;

use Zana\HTMLTag\HTMLtag;

class TableHead extends HTMLTag {
    public function __construct() {
        parent::__construct('thead'); // Set the tag to 'thead'
    }

    public function addRow(TableRow $row) {
        $this->addContent($row);
        return $this; // Allow method chaining
    }
}