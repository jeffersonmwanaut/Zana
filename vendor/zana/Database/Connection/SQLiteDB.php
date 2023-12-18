<?php namespace Zana\Database\Connection;

use PDO;

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
            $this->pdo = new PDO('sqlite:' . $db);
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