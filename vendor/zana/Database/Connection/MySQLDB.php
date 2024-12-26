<?php namespace Zana\Database\Connection;

use PDO;
use PDOException;

class MySQLDB
{
    /**
     * @var PDO|null
     */
    protected ?PDO $pdo = null;

    /**
     * MySQLDatabase constructor
     */
    public function __construct()
    {
        $db = CONFIG['db']['mysql'];
        if (!empty($db)) {
            try {
                $this->pdo = new PDO(
                    'mysql:host=' . $db['host'] . '; port=' . $db['port'] . '; dbname=' . $db['name'], 
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
     * Get the PDO instance
     * @return PDO|null
     */
    public function pdo(): ?PDO
    {
        return $this->pdo;
    }

    /**
     * Close the database connection
     */
    public function close(): void
    {
        $this->pdo = null; // This will close the connection
    }

}