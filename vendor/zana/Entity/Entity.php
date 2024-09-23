<?php namespace Zana\Entity;

use Exception;

/**
 * Class Entity
 * @package Zana\Entity
 */
class Entity
{

    /**
     * Entity constructor.
     * @param array $data
     * @throws \Exception
     */
    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function ignoreProperties():?array
    {
        return [];
    }

    /**
     * This method assigns values from table columns to the object properties
     * To make it easy I set some simple rules :<br>
     * <ul>
     * <li>Table columns are named in snake case style</li>
     * <li>Setters are named in lower camel case style and start with <b>set</b> keyword</li>
     * </ul>
     * Then if we want to find the setter name, we just need to do the following :<br>
     * <ul>
     * <li>Substitute underscores for whitespaces with <b>str_replace()</b> function</li>
     * <li>Capitalize the first letter of each word with <b>ucwords</b> function</li>
     * <li>Remove whitespaces with <b>str_replace()</b> function</li>
     * </ul>
     * Eg. <b>my_attribute</b> will have <b>setMyAttribute</b> as setter.
     * @param array $data
     * @throws \Exception
     */
    public function hydrate(array $data)
    {
        foreach ($data as $property => $value) {
            if (substr($property, -3) == '_id') {
                // Property is an object
                /**
                 * For a property to be detected as an object, its type must be explicitly added when declared in the class.
                 * Example: protected User $user;
                 * In addition, the column name in the database must end with "_id".
                 * Example: user_id
                 */
                $property = str_replace('_id', '', $property);
                $objectProperty = str_replace(' ', '', str_replace('_', ' ', $property));
                $reflectionClass = new \ReflectionClass($this);
                $reflectionClassProperties = $reflectionClass->getProperties();
                foreach ($reflectionClassProperties as $reflectionClassProperty) {
                    if ($reflectionClassProperty->getName() == $objectProperty) {
                        $objectProperty = $reflectionClassProperty->getType()->getName();
                        break;
                    }
                }
                // Find manager
                $objectPropertyParts = explode('\\', $objectProperty);
                $manager = $objectPropertyParts[count($objectPropertyParts) - 3] . '\\Manager\\' . str_replace('Entity', 'Manager', $objectPropertyParts[count($objectPropertyParts) - 1]);
                // Check that the manager class exists before trying to use it
                if (class_exists($manager)) {
                    // Create instance of manager
                    $manager = new $manager();
                    $value = $manager->find(['conditions' => ['id' => $value]])->first();
                } else {
                    // Exception is thrown here if class not found.
                    throw new Exception('Undefined class ' . $manager . ' in ' . get_class($this) . ' on line ' . __LINE__);
                }
            }
            // Substitute underscores for whitespaces, capitalize the first letter of each word and remove whitespaces.
            $setter = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
            if (method_exists($this, $setter)) {
                if(!$value instanceof \DateTime) {
                    if (!is_null($value) && is_string($value)) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                        if ($date !== false) {
                            // it's a date
                            $value = $date;
                        }
                    } 
                }
                
                // Call method only if exists, otherwise throw exception in else bloc.
                $this->$setter($value);
            } else {
                // Exception is thrown here if method not found.
                throw new Exception('Call to undefined method ' . $setter . ' in ' . get_class($this) . ' on line ' . __LINE__);
            }
        }
    }

}