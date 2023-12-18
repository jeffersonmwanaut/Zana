<?php namespace Zana\Database\Connection;

use PDO;

class PostgreSQLDB
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct()
    {
        $db = CONFIG['db']['pgsql'];
        if (!empty($db)) {
            $this->pdo = new PDO('pgsql:host=' . $db['host'] . '; port=' . $db['port'] . '; dbname=' . $db['name'], $db['user'], $db['password']);
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