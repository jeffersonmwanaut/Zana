<?php namespace Zana\HTMLTag\Form;

class Textarea extends HTMLTag {
    public function __construct($id = '', $name = '', Label $label = null, $rows = 4, $cols = 50) {
        parent::__construct('textarea'); // Set the tag to 'textarea'
        $this->setId($id);
        $this->setName($name);
        $this->setRows($rows);
        $this->setCols($cols);
        if ($label) {
            $this->label = $label;
        }
    }

    public function setId($id) {
        return $this->setAttribute('id', $id);
    }

    public function setName($name) {
        return $this->setAttribute('name', $name);
    }

    public function setRows($rows) {
        return $this->setAttribute('rows', $rows);
    }

    public function setCols($cols) {
        return $this->setAttribute('cols', $cols);
    }

    public function render() {
        // Render the label if it exists
        $labelHtml = $this->label ? $this->label->render() : '';

        // Render the input element using the HTMLTag's render method
        $textareaHtml = parent::render(); // Use the parent render method for the textarea

        return $labelHtml . $inputHtml; // Combine label and textarea HTML
    }
}