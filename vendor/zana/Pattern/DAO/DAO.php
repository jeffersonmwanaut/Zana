<?php namespace Zana\Pattern\DAO;

abstract class DAO
{
    /**
     * @param $object
     * @return mixed
     */
    public abstract function create($object, $ignoreProperties = []);

    /**
     * @param $object
     * @return mixed
     */
    public abstract function update($object, $ignoreProperties = []);

    /**
     * @param $object
     * @return mixed
     */
    public abstract function delete($object);

    /**
     * @param array $filter Database filter
     * @return mixed
     */
    public abstract function read($filter = ['fields' => [], 'conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]]);

    /**
     * @param $object
     * @return mixed
     */
    public abstract function save($object, $ignoreProperties = []);
}