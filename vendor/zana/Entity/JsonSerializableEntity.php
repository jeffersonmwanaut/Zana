<?php namespace Zana\Entity;

/**
 * Class JsonSerializableEntity
 * <br>Inherit from this class to be serializable
 * <br>/!\ Child class properties must have protected scope.
 * @package Zana\Entity
 */
class JsonSerializableEntity extends Entity implements \JsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return get_object_vars(get_class($this));
    }
}