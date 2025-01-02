<?php namespace Zana\HTMLTag\Form;

class Select extends HTMLTag {
    protected $label;

    public function __construct($id, $name, Label $label = null) {
        parent::__construct('select');
        $this->setAttribute('name', $name);
        $this->selectedValue = $selectedValue;

        if ($label) {
            $this->label = $label; // Store the Label object
        }
    }

    public function setId($id) {
        return $this->setAttribute('id', $id);
    }

    public function setName($name) {
        return $this->setAttribute('name', $name);
    }

    public function addOption(Option $option) {
        $this->addContent($option);
        return $this;
    }

    public function clearOptions() {
        return $this->clearContent();
    }

    public function render() {
        // Render the label if it exists
        $labelHtml = $this->label ? $this->label->render() : '';

        // Render the select element using the HTMLTag's render method
        $selectHtml = parent::render(); // Use the parent render method for the select

        return $labelHtml . $selectHtml; // Combine label and select HTML
    }
}