<?php namespace Zana\Database\Connection;

use PDO;
use PDOException;

class SQLiteDB
{
    /**
     * @var PDO
     */
    protected $pdo;

    public function __construct()
    {
        $db = CONFIG['db']['sqlite'];
        if (!empty($db)) {
            try {
                $this->pdo = new PDO('sqlite:' . $db);
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