<?php namespace Zana\Database\Connection;

use PDO;

class MySQLDB
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * MySQLDatabase constructor
     */
    public function __construct()
    {
        $db = CONFIG['db']['mysql'];
        if (!empty($db)) {
            $this->pdo = new PDO('mysql:host=' . $db['host'] . '; port=' . $db['port'] . '; dbname=' . $db['name'], $db['user'], $db['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * @return PDO
     */
    public function pdo()
    {
        return $this->pdo;
    }

}