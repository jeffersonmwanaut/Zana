<?php namespace Zana\Pattern\DAO;

interface IDAO
{
    public function create($object);
    public function read($filter = ['fields' => [], 'conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]]);
    public function find($filter = ['conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]]);
    public function update($object);
    public function delete($object);
    public function save($object);
    public function count($filter = ['conditions' => [], 'limit' => ['skip' => 0, 'range' => 20]]);
    public function queryBuilder();
    public function executeQuery($queryString, $conditions = []);
}