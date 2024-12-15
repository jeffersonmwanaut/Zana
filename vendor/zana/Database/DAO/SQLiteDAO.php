<?php namespace Zana\Database\DAO;

use Exception;
use Zana\Pattern\DAO\DAO;
use Zana\Database\DbFactory;
use Zana\Database\DbType;
use Zana\Database\ResultSet;

/**
 * Class SQLiteDAO
 * @package Zana\Database
 */
class SQLiteDAO extends DAO
{
    protected $table;
    protected $entity;
    protected $pdo;

    public function __construct($table = null)
    {
        $this->entity = preg_replace('#(Manager|DAO|Dao)#', 'Entity', get_called_class());
        if (is_null($table)) {
            $table = preg_replace('#(Entity)#', '', $this->entity);
            $parts = explode('\\', $table);
            $table = end($parts);
            $table = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $table));
            $table = substr($table, 1);
        }
        $this->table = $table;
        $this->pdo = (new DbFactory())->create(DbType::SQLITE)->pdo();
    }

    public function create($object)
    {
        if (is_null($object)) {
            return false;
        }

        $queryParams = [];
        $objectProperties = (new \ReflectionClass($object))->getProperties();
        $fields = [];

        foreach ($objectProperties as $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if (in_array($property, $object->ignoreProperties())) {
                continue;
            }

            $method = method_exists($object, 'is' . ucfirst($property)) ? 'is' . ucfirst($property) : 'get' . ucfirst($property);
            
            if (!method_exists($object, $method)) {
                throw new Exception('Call to undefined method ' . $method . ' in ' . get_class($this));
            }

            $value = $object->$method();
            $field = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $property));

            // Handle object values
            if (is_object($value) && method_exists($value, 'getId')) {
                $value = $value->getId();
                $field .= '_id';
            }

            $queryParams[$field] = $value;
            $fields[] = "\"$field\""; // Collect fields for the query
        }

        // Build the query string
        $queryString = "INSERT INTO \"$this->table\" (" . implode(", ", $fields) . ") VALUES (" . implode(", ", array_map(fn($field) => ":$field", array_keys($queryParams))) . ")";

        $query = $this->pdo->prepare($queryString);

        // Bind parameters
        foreach ($queryParams as $field => $value) {
            $paramType = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $query->bindValue($field, $value, $paramType);
        }

        $query->execute();

        if ($query->rowCount() > 0) {
            $object->setId($this->pdo->lastInsertId());
            return $object;
        }

        return false;
    }

    public function update($object)
    {
        if (is_null($object)) {
            return false;
        }

        $queryParams = [];
        $fields = [];
        $objectProperties = (new \ReflectionClass($object))->getProperties();

        foreach ($objectProperties as $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if (in_array($property, $object->ignoreProperties())) {
                continue;
            }

            $method = method_exists($object, 'is' . ucfirst($property)) ? 'is' . ucfirst($property) : 'get' . ucfirst($property);
            
            if (!method_exists($object, $method)) {
                throw new Exception('Call to undefined method ' . $method . ' in ' . get_class($this));
            }

            $value = $object->$method();
            $field = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $property));

            // Handle object values
            if (is_object($value) && method_exists($value, 'getId')) {
                $value = $value->getId();
                $field .= '_id';
            }

            $queryParams[$field] = $value;
            $fields[] = "\"$field\" = :$field"; // Collect fields for the query
        }

        // Build the query string
        $queryString = "UPDATE \"$this->table\" SET " . implode(", ", $fields) . " WHERE id = :id";
        $queryParams['id'] = $object->getId();

        $query = $this->pdo->prepare($queryString);

        // Bind parameters
        foreach ($queryParams as $field => $value) {
            $paramType = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $query->bindValue($field, $value, $paramType);
        }

        return $query->execute() ? $object : false;
    }

    public function delete($object)
    {
        if (is_null($object)) {
            return false;
        }

        $queryString = "DELETE FROM \"$this->table\" WHERE id = :id";
        $query = $this->pdo->prepare($queryString);
        $query->bindValue(':id', $object->getId(), \PDO::PARAM_INT);

        if ($query->execute() && $query->rowCount() > 0) {
            return $object;
        }

        return false;
    }

    public function read($filter = ['fields' => [], 'conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]])
    {
        $fields = $filter['fields'] ?? [];
        $conditions = $filter['conditions'] ?? [];
        $order = $filter['order'] ?? 1;
        $limit = $filter['limit'] ?? ['skip' => 0, 'range' => 20];

        // Build fields string
        $fieldString = empty($fields) ? "*" : implode(", ", array_map(fn($field) => "\"$field\"", $fields));

        // Build conditions string
        $queryConditions = [];
        $queryConditionString = empty($conditions) ? "1" : "";

        $paramIndex = 0;
        
        if (!empty($conditions)) {
            foreach ($conditions as $condition => $value) {
                if(is_array($value)) {
                    foreach($value as $val) {
                        $paramName = "{$condition}_{$paramIndex}";
                        $val = $val ?? ' NULL ';
                        $operator = (substr($val, 0, 1) === '!') ? '<>' : '=';
                        $queryConditionString .= sprintf("\"%s\" %s :%s OR ", $condition, $operator, $paramName);
                        $queryConditions[$paramName] = "$val";
                        $paramIndex++;
                    }
                    $queryConditionString = rtrim($queryConditionString, ' OR ');
                    $queryConditionString .= ' AND ';
                } else {
                    $paramName = "{$condition}_{$paramIndex}";
                    $value = $value ?? ' NULL ';
                    $operator = (substr($value, 0, 1) === '!') ? '<>' : '=';
                    $queryConditionString .= sprintf("\"%s\" %s :%s AND ", $condition, $operator, $paramName);
                    $queryConditions[$paramName] = "$value";
                    $paramIndex++;
                }
            }

            $queryConditionString = rtrim($queryConditionString, ' AND ');
        }

        // Build the base query
        $queryString = "SELECT $fieldString FROM \"$this->table\" WHERE $queryConditionString ORDER BY $order";

        // Append LIMIT only if range is greater than 0
        if ($limit['range'] > 0) {
            $queryString .= " LIMIT :range OFFSET :skip";
        }

        $query = $this->pdo->prepare($queryString);

        // Bind parameters
        foreach ($queryConditions as $field => $value) {
            $paramType = match (true) {
                is_int($value) => \PDO::PARAM_INT,
                is_bool($value) => \PDO::PARAM_BOOL,
                $value instanceof \DateTime => \PDO::PARAM_STR,
                default => \PDO::PARAM_STR,
            };

            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $query->bindValue($field, $value, $paramType);
        }

        // Bind limit parameters only if they are set
        if ($limit['range'] > 0) {
            $query->bindValue('skip', $limit['skip'], \PDO::PARAM_INT);
            $query->bindValue('range', $limit['range'], \PDO::PARAM_INT);
        }

        $query->execute();
        $data = [];
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = new $this->entity($row);
        }

        return new ResultSet($data);
    }

    /**
     * @param array $filter Database filter
     * @return mixed
     */
    public function find($filter = ['conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]])
    {
        $conditions = $filter['conditions'] ?? [];
        $order = $filter['order'] ?? 1;
        $limit = $filter['limit'] ?? ['skip' => 0, 'range' => 20];

        // Build conditions string
        $queryConditions = [];
        $queryConditionString = empty($conditions) ? "1" : "";

        $paramIndex = 0; // To create unique parameter names

        if (!empty($conditions)) {
            foreach ($conditions as $condition => $value) {
                if(is_array($value)) {
                    foreach($value as $val) {
                        $paramName = "{$condition}_{$paramIndex}"; // Unique parameter name
                        $queryConditionString .= "\"$condition\" LIKE :$paramName OR ";
                        $val = $val ?? ' NULL ';
                        $queryConditions[$paramName] = "%$val%";
                        $paramIndex++;
                    }
                } else {
                    $paramName = "{$condition}_{$paramIndex}"; // Unique parameter name
                    $queryConditionString .= "\"$condition\" LIKE :$paramName OR ";
                    $value = $value ?? ' NULL ';
                    $queryConditions[$paramName] = "%$value%"; // Use LIKE with wildcards
                    $paramIndex++;
                }
            }

            // Remove the trailing ' OR ' from the query condition string
            $queryConditionString = rtrim($queryConditionString, ' OR ');
        }

        // Build the query string
        $queryString = "SELECT * FROM \"$this->table\" WHERE $queryConditionString ORDER BY $order";

        // Append LIMIT only if range is greater than 0
        if ($limit['range'] > 0) {
            $queryString .= " LIMIT :range OFFSET :skip";
        }

        $query = $this->pdo->prepare($queryString);

        // Bind parameters
        foreach ($queryConditions as $field => $value) {
            $paramType = match (true) {
                is_int($value) => \PDO::PARAM_INT,
                is_bool($value) => \PDO::PARAM_BOOL,
                $value instanceof \DateTime => \PDO::PARAM_STR,
                default => \PDO::PARAM_STR,
            };

            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $query->bindValue($field, $value, $paramType);
        }

        // Bind limit parameters only if they are set
        if ($limit['range'] > 0) {
            $query->bindValue('skip', $limit['skip'], \PDO::PARAM_INT);
            $query->bindValue('range', $limit['range'], \PDO::PARAM_INT);
        }

        $query->execute();
        $data = [];
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = new $this->entity($row);
        }

        return new ResultSet($data);
    }

    /**
     * @param $object
     * @return bool|mixed
     * @throws \Exception
     */
    public function save($object)
    {
        if (is_null($object)) {
            return false;
        }

        if (!method_exists($object, 'getId')) {
            throw new \Exception('Class ' . get_class($object) . ' does not have method getId');
        }

        // Determine whether to create or update
        return !empty($object->getId()) ? $this->update($object, $object->ignoreProperties()) : $this->create($object, $object->ignoreProperties());
    }

    /**
     * @param array $filter Database filter
     * @return int
     */
    public function count($filter = ['conditions' => []])
    {
        $conditions = $filter['conditions'] ?? [];

        // Build conditions string
        $queryConditions = [];
        $queryConditionString = empty($conditions) ? "1" : "";

        $paramIndex = 0;

        if (!empty($conditions)) {
            foreach ($conditions as $condition => $value) {
                if(is_array($value)) {
                    foreach($value as $val) {
                        $paramName = "{$condition}_{$paramIndex}";
                        $val = $val ?? ' NULL ';
                        $operator = (substr($val, 0, 1) === '!') ? '<>' : '=';
                        $queryConditionString .= sprintf("\"%s\" %s :%s OR ", $condition, $operator, $paramName);
                        $queryConditions[$paramName] = "$val";
                        $paramIndex++;
                    }
                    $queryConditionString = rtrim($queryConditionString, ' OR ');
                    $queryConditionString .= ' AND ';
                } else {
                    $paramName = "{$condition}_{$paramIndex}";
                    $value = $value ?? ' NULL ';
                    $operator = (substr($value, 0, 1) === '!') ? '<>' : '=';
                    $queryConditionString .= sprintf("\"%s\" %s :%s AND ", $condition, $operator, $paramName);
                    $queryConditions[$paramName] = "$value";
                    $paramIndex++;
                }
            }

            // Clean up the condition string
            $queryConditionString = rtrim($queryConditionString, ' AND ');
        }

        // Build the count query
        $queryString = "SELECT COUNT(*) as count FROM \"$this->table\" WHERE $queryConditionString";

        $query = $this->pdo->prepare($queryString);

        // Bind parameters
        foreach ($queryConditions as $field => $value) {
            $paramType = match (true) {
                is_int($value) => \PDO::PARAM_INT,
                is_bool($value) => \PDO::PARAM_BOOL,
                $value instanceof \DateTime => \PDO::PARAM_STR,
                default => \PDO::PARAM_STR,
            };

            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $query->bindValue($field, $value, $paramType);
        }

        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return (int) $result['count'];
    }
}