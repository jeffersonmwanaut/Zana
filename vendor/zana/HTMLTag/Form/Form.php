<?php namespace Zana\HTMLTag\Form;

use Zana\HTMLTag\Div;

class Form extends HTMLTag {
    public function __construct($action = '', $method = 'POST') {
        parent::__construct('form');
        $this->setAction($action);
        $this->setMethod($method);
        $this->setAttribute('class', 'form');
    }

    public function setAction($action) {
        return $this->setAttribute('action', $action);
    }

    public function setMethod($method) {
        return $this->setAttribute('method', $method);
    }

    public function addField(Input $input) {
        $div = new Div();
        $div->setAttribute('class', 'mb-3');
        $div->addContent($input);
        $this->addContent($div);
        return $this; // Allow method chaining
    }

    public function addButton(Button $button) {
        $div = new Div();
        $div->setAttribute('class', 'mb-3');
        $div->addContent($input);
        $this->addContent($button);
        return $this;
    }

    public function clearFields() {
        // Filter out all divs that contain Input objects from the content
        $this->content = array_filter($this->content, function($item) {
            // Check if the item is a Div and contains an Input
            if ($item instanceof Div) {
                // Check if the Div contains an Input
                foreach ($item->getContent() as $contentItem) {
                    if ($contentItem instanceof Input) {
                        return false; // Remove this Div
                    }
                }
            }
            return true; // Keep non-Div items and Divs without Inputs
        });
        return $this;
    }

    public function clearButtons() {
        // Filter out all divs that contain Button objects from the content
        $this->content = array_filter($this->content, function($item) {
            // Check if the item is a Div and contains a Button
            if ($item instanceof Div) {
                // Check if the Div contains a Button
                foreach ($item->getContent() as $contentItem) {
                    if ($contentItem instanceof Button) {
                        return false; // Remove this Div
                    }
                }
            }
            return true; // Keep non-Div items and Divs without Buttons
        });
        return $this;
    }

    public function clearControls() {
        return $this->clearContent();
    }
}