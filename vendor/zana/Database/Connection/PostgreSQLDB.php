<?php namespace Zana\Database\Connection;

use PDO;
use PDOException;

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
            try {
                $this->pdo = new PDO(
                    'pgsql:host=' . $db['host'] . '; port=' . $db['port'] . '; dbname=' . $db['name'], 
                    $db['user'], 
                    $db['password']
                );
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new PDOException('Database connection failed: ' . $e->getMessage());
            }
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