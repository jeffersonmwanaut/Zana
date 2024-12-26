<?php namespace Zana\Database\Connection;

use PDO;
use PDOException;

class SQLiteDB
{
    /**
     * @var PDO|null
     */
    protected ?PDO $pdo = null;

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