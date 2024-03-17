<?php namespace Zana\Pattern\DAO;

interface IDAO
{
    public function create($object);
    public function read($filter = ['fields' => [], 'conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]]);
    public function update();
    public function delete($object);
    public function save($object);
}