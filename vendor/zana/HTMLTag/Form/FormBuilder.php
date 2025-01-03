<?php namespace Zana\HTMLTag\Form;

class FormBuilder {
    public static function createFormForEntity($entity, $action = '', $method = 'POST') {
        $form = new Form($action, $method);
        
        // Use reflection to get the properties of the entity
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            // Check if the property is ignored
            if (in_array($propertyName, $entity->ignoreProperties())) {
                continue;
            }

            // Determine the input type based on the property type
            $inputType = 'text'; // Default to text input
            $label = new Label($propertyName, ucfirst(str_replace('_', ' ', $propertyName))); // Create a label from the property name

            if ($propertyType instanceof \ReflectionNamedType) {
                $typeName = $propertyType->getName();
                if ($typeName === 'int') {
                    $inputType = 'number';
                } elseif ($typeName === 'float') {
                    $inputType = 'number';
                } elseif ($typeName === 'string') {
                    $inputType = 'text';
                } elseif ($typeName === 'DateTime') {
                    $inputType = 'date';
                } elseif (class_exists($typeName)) {
                    // Handle object types (e.g., foreign key relationships)
                    $inputType = 'select'; // For example, create a dropdown
                }
            }

            // Create the input field
            if ($propertyType instanceof \ReflectionNamedType && class_exists($propertyType->getName())) {
                // For object types, create a select input
                $select = new Select($propertyName, $propertyName, $label);

                // Fetch options for the select input
                $managerClass = str_replace('Entity', 'Manager', $propertyType->getName());
                if (class_exists($managerClass)) {
                    $manager = new $managerClass();
                    $relatedEntities = $manager->read()->all(); // Fetch all related entities
                    foreach ($relatedEntities as $relatedEntity) {
                        $value = $relatedEntity->getId(); // Assuming each related entity has an `getId()` method

                        // Use the optionDisplayText method to get the display text
                        $text = $relatedEntity->optionDisplayText();

                        // Add the option to the select input
                        $select->addOption(new Option($value, $text));
                    }
                }

                $form->addField($select);
            } else {
                // Create a standard input field
                $input = new Input($inputType, $propertyName, $propertyName, $label);
                $form->addField($input);
            }
        }

        return $form;
    }
}