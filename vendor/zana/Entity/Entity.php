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
            // Substitute underscores for whitespaces, capitalize the first letter of each word and remove whitespaces.
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
            if (method_exists($this, $method)) {
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                if ($date !== false) {
                    // it's a date
                    $value = $date;
                }
                // Call method only if exists, otherwise throw exception in else bloc.
                $this->$method($value);
            } else {
                // Exception is thrown here if method not found.
                throw new Exception('Call to undefined method ' . $method . ' in ' . get_class($this) . ' on line ' . __LINE__);
            }
        }
    }

}