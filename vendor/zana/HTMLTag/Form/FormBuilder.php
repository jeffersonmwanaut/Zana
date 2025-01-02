<?php namespace Zana\HTMLTag\Form;

use Zana\Entity;

class FormBuilder {
    public static function createFormForEntity(Entity $entity, $action = '', $method = 'POST') {
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
                    $relatedEntities = $manager->read(); // Fetch all related entities
                    foreach ($relatedEntities as $relatedEntity) {
                        $value = $relatedEntity->getId(); // Assuming each related entity has an `getId()` method

                        // Use reflection to find a suitable property for the display text
                        $relatedReflectionClass = new \ReflectionClass($relatedEntity);
                        $text = null;

                        // Check for a property that could serve as a display name
                        foreach ($relatedReflectionClass->getProperties() as $relatedProperty) {
                            $relatedPropertyName = $relatedProperty->getName();
                            // Check if the property has a getter method
                            $getterMethod = 'get' . ucfirst($relatedPropertyName);
                            if ($relatedReflectionClass->hasMethod($getterMethod)) {
                                $getter = $relatedReflectionClass->getMethod($getterMethod);
                                if ($getter->isPublic()) {
                                    $text = $relatedEntity->$getterMethod(); // Call the getter method
                                    break; // Use the first suitable property found
                                }
                            }
                        }

                        // Fallback to using the ID if no suitable property is found
                        if ($text === null) {
                            $text = (string)$value; // Use the ID as the text
                        }

                        $select->addOption(new Option($value, $text));
                    }
                }

                $form->addField($select);
            } else {
                // Create a standard input field
                $input = new Input($propertyName, $propertyName, $inputType, $label);
                $form->addField($input);
            }
        }

        // Add a submit button
        $submitButton = new Button('Submit');
        $form->addButton($submitButton);

        return $form;
    }
}