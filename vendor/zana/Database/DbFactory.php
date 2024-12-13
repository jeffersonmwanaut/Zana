<?php namespace Zana\Database;

use Zana\Database\Connection\MySQLDB;
use Zana\Database\Connection\PostgreSQLDB;
use Zana\Database\Connection\SQLiteDB;

class DbFactory extends \Zana\Pattern\AbstractFactory
{
    public function create($type)
    {
        switch ($type) {
            case DbType::MYSQL:
                return new MySQLDB();
            case DbType::POSTGRESQL:
                return new PostgreSQLDB();
            case DbType::SQLITE:
                return new SQLiteDB();
            default:
                return false;
        }
    }
}