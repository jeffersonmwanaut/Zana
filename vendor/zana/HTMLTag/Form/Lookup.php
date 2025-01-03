<?php namespace Zana\HTMLTag\Form;

use Zana\HTMLTag\HTMLTag;

class Lookup extends HTMLTag
{
    protected Label $label;

    public function __construct($id = '', $name = '', $entitiesArray = [], Label $label = null)
    {
        parent::__construct('div');
        $this->setAttribute('class', 'input-group');

        // Create the select element
        $select = new Select($id, $name);
        $select->setAttribute('class', 'form-control form-control-disabled');
        $required = $this->getAttribute('required');
        if($required) {
            $select->setAttribute('required', $required);
        }
        $select->addOption((new Option('','-- Select --', true))->setAttribute('disabled', 'disabled'));

        foreach($entitiesArray as $entity) {
            $select->addOption(new Option($entity->getId(), $entity->optionDisplayText()));
        }

        // Create the search icon
        $icon = new HTMLTag('i');
        $icon->setAttribute('class', 'fa-solid fa-magnifying-glass text-secondary');

        // Create the anchor element for the modal trigger
        $anchor = new HTMLTag('a');
        $anchor->setAttribute('href', '#')
                ->setAttribute('class', 'input-group-text text-decoration-none')
                ->setAttribute('data-bs-toggle', 'modal')
                ->setAttribute('data-bs-target', '#' . $id . 'Lookup')
                ->addContent($icon);

        // Add the select and anchor to the input group
        $this->addContent($select);
        $this->addContent($anchor);

        // Set the label if provided
        if ($label) {
            $label->setAttribute('for', $id);
            $label->setAttribute('class', 'form-label');
            $this->label = $label;
        }
    }

    public function render() {
        $labelHtml = $this->label ? $this->label->render() : '';
        $lookupHtml = parent::render();
        return $labelHtml . $lookupHtml;
    }
}