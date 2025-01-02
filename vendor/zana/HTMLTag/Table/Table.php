<?php namespace Zana\HTMLTag\Table;

class Table extends HTMLTag {
    public function __construct() {
        parent::__construct('table');
    }

    public function setHead(TableHead $head) {
        $this->addContent($head);
        return $this;
    }

    public function setBody(TableBody $body) {
        $this->addContent($body);
        return $this;
    }

    public function setFoot(TableFoot $foot) {
        $this->addContent($foot);
        return $this;
    }
}