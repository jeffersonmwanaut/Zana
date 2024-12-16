<?php namespace Zana\Pattern\DAO;

abstract class DAO
{
    /**
     * @param $object
     * @return mixed
     */
    public abstract function create($object);

    /**
     * @param $object
     * @return mixed
     */
    public abstract function update($object);

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
     * @param array $filter Database filter
     * @return mixed
     */
    public abstract function find($filter = ['conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]]);

    /**
     * @param $object
     * @return mixed
     */
    public abstract function save($object);

    /**
     * @param array $filter Database filter
     * @return int
     */
    public abstract function count($filter = ['conditions' => [], 'limit' => ['skip' => 0, 'range' => 20]]);

    /**
     * @return mixed
     */
    public abstract function queryBuilder();

    /**
     * @param string $queryString
     * @param array $conditions
     * @return mixed
     */
    public abstract function executeQuery($queryString, $conditions = []);
}