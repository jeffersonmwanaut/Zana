<?php namespace Zana\Database\DAO;

use Exception;
use Zana\Pattern\DAO\DAO;
use Zana\Database\DbFactory;
use Zana\Database\DbType;

/**
 * Class MySQLDAO
 * @package Zana\Database
 */
class MySQLDAO extends DAO
{
    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $entity;
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * MySQLDAO constructor.
     * @param string $table The table we are working with. Default value is the name of the entity class without "Entity" suffix.
     */
    public function __construct($table = null)
    {
        if (is_null($table)) {
            $this->entity = preg_replace('#(Manager|DAO|Dao)#', 'Entity', get_called_class());

            $table = preg_replace('#(Entity)#', '', $this->entity);
            $parts = explode('\\', $table);
            $table = end($parts);
            $table = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $table));
            $table = substr($table, 1);
            $this->table = $table;
        }
        $this->pdo = (new DbFactory())->create(DbType::MYSQL)->pdo();
    }

    /**
     * @param $object
     * @return bool|mixed
     * @throws Exception
     */
    public function create($object, $ignoreProperties = [])
    {
        $queryString = "INSERT INTO `" . $this->table . "` SET ";
        $queryParams = [];
        $objectProperties = (new \ReflectionClass($object))->getProperties();
        foreach ($objectProperties as $key => $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if(in_array($property, $ignoreProperties)) {
                continue;
            }

            if(method_exists($object, 'is' . ucfirst($property))) {
                $method = 'is' . ucfirst($property);
            } else {
                $method = 'get' . ucfirst($property);
            }
            
            if (method_exists($object, $method)) {
                // Get value from property getter
                $value = $object->$method();
                // Get database field corresponding to property name
                $field = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $property));
                // Get data and data type, identified by field name, as query param.
                $queryParams[$field] = $value;
                // Concat the field and nominative param for prepared query.
                $queryString .= "`" . $field . "` = :" . $field . ", ";
            } else {
                // Exception is thrown here if method not found.
                throw new Exception('Call to undefined method ' . $method . ' in ' . get_class($this) . ' on line ' . __LINE__);
            }
        }
        $queryString = substr($queryString, 0, -2);
        $query = $this->pdo->prepare($queryString);

        foreach ($queryParams as $field => $value) {
            if (is_int($value)) $query->bindValue($field, $value, \PDO::PARAM_INT);
            elseif (is_bool($value)) $query->bindValue($field, $value, \PDO::PARAM_BOOL);
            elseif($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
                $query->bindValue($field, $value, \PDO::PARAM_STR);
            }
            else $query->bindValue($field, $value, \PDO::PARAM_STR);
        }
        $query->execute();
        if ($query->rowCount() > 0) {
            $object->setId($this->pdo->lastInsertId());
            return $object;
        }
        return false;
    }

    /**
     * @param $object
     * @return bool|mixed
     * @throws Exception
     */
    public function update($object, $ignoreProperties = [])
    {
        $queryString = "UPDATE `" . $this->table . "` SET ";
        $queryParams = [];
        $objectProperties = (new \ReflectionClass($object))->getProperties();
        foreach ($objectProperties as $key => $reflectionProperty) {
            $property = $reflectionProperty->getName();

            if(in_array($property, $ignoreProperties)) {
                continue;
            }
            
            if(method_exists($object, 'is' . ucfirst($property))) {
                $method = 'is' . ucfirst($property);
            } else {
                $method = 'get' . ucfirst($property);
            }
            
            if (method_exists($object, $method)) {
                // Get value from property getter
                $value = $object->$method();
                // Get database field corresponding to property name
                $field = strtolower(preg_replace('#[A-Z]{1}#', '_' . '$0', $property));
                // Get data and data type, identified by field name, as query param.
                $queryParams[$field] = $value;
                // Concat the field and nominative param for prepared query.
                $queryString .= "`" . $field . "` = :" . $field . ", ";
            } else {
                // Exception is thrown here if method not found.
                throw new Exception('Call to undefined method ' . $method . ' in ' . get_class($this) . ' on line ' . __LINE__);
            }
        }
        $queryString = substr($queryString, 0, -2);
        $queryString .= " WHERE `id` = :id";
        $query = $this->pdo->prepare($queryString);

        foreach ($queryParams as $field => $value) {
            if (is_int($value)) $query->bindValue($field, $value, \PDO::PARAM_INT);
            elseif (is_bool($value)) $query->bindValue($field, $value, \PDO::PARAM_BOOL);
            elseif($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
                $query->bindValue($field, $value, \PDO::PARAM_STR);
            }
            else $query->bindValue($field, $value, \PDO::PARAM_STR);
        }
        $query->bindValue('id', $object->getId(), \PDO::PARAM_INT);

        if ($query->execute()) {
            return $object;
        }
        return false;
    }

    /**
     * @param $object
     * @return bool|mixed
     */
    public function delete($object)
    {
        $queryString = "DELETE FROM `" . $this->table . "`";
        $queryString .= " WHERE `id` = :id";
        $query = $this->pdo->prepare($queryString);
        $query->bindValue('id', $object->getId(), \PDO::PARAM_INT);

        $query->execute();
        if ($query->rowCount() > 0) {
            return $object;
        }
        return false;
    }

    /**
     * @param array $filter Database filter
     * @return mixed|void
     */
    public function read($filter = ['fields' => [], 'conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]])
    {
        $fields = isset($filter['fields']) == false ? [] : $filter['fields'];
        $conditions = isset($filter['conditions']) == false ? [] : $filter['conditions'];
        $order = isset($filter['order']) == false ? 1 : $filter['order'];
        $limit = isset($filter['limit']) == false ? ['skip' => 0, 'range' => 20] : $filter['limit'];

        // Build fields string
        $fieldString = null;
        if (empty($fields)) {
            $fields = "*";
            $fieldString = $fields;
        } else {
            $fields = explode(', ', $fields);
            foreach ($fields as $field) {
                $fieldString .= "`" . $field . "`, ";
            }
            $fieldString = substr($fieldString, 0, -2);
        }

        $queryConditions = [];
        $queryConditionString = null;
        if (empty($conditions)) {
            $conditions = "1";
            $queryConditionString = $conditions;
        } else {
            foreach ($conditions as $condition => $value) {
                $queryConditions[$condition] = $value;
                if(substr($value, 0, 1) === '!') {
                    $queryConditionString .= "`" . $condition . "` <> :" . $condition . " AND ";
                } else {
                    $queryConditionString .= "`" . $condition . "` = :" . $condition . " AND ";
                }
            }
            $queryConditionString = substr($queryConditionString, 0, -5);
        }

        $queryString = "SELECT " . $fieldString . " FROM `" . $this->table . "` WHERE " . $queryConditionString . " ORDER BY " . $order . " LIMIT :skip, :range";
        $query = $this->pdo->prepare($queryString);
        foreach ($queryConditions as $field => $value) {
            if(substr($value, 0, 1) === '!') {
                $value = substr($value, 1);
            }
            if (is_int($value)) $query->bindValue($field, $value, \PDO::PARAM_INT);
            elseif (is_bool($value)) $query->bindValue($field, $value, \PDO::PARAM_BOOL);
            elseif($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
                $query->bindValue($field, $value, \PDO::PARAM_STR);
            }
            else $query->bindValue($field, $value, \PDO::PARAM_STR);
        }
        $query->bindValue('skip', $limit['skip'], \PDO::PARAM_INT);
        $query->bindValue('range', $limit['range'], \PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = new $this->entity($row);
        }
        return isset($data) ? $data : [];
    }

    /**
     * @param array $filter Database filter
     * @return bool
     */
    public function find($filter = ['conditions' => [], 'order' => 1, 'limit' => ['skip' => 0, 'range' => 20]])
    {
        $conditions = isset($filter['conditions']) == false ? [] : $filter['conditions'];
        $order = isset($filter['order']) == false ? 1 : $filter['order'];
        $limit = isset($filter['limit']) == false ? ['skip' => 0, 'range' => 20] : $filter['limit'];

        $queryConditions = [];
        $queryConditionString = null;
        if (empty($conditions)) {
            $conditions = "1";
            $queryConditionString = $conditions;
        } else {
            foreach ($conditions as $condition => $value) {
                $queryConditions[$condition] = $value;
                if ($condition === 'id') {
                    $queryConditionString .= "`" . $condition . "` = :" . $condition . ",   ";
                    break;
                }
                $queryConditionString .= "`" . $condition . "` LIKE :" . $condition . " OR ";
            }
            $queryConditionString = substr($queryConditionString, 0, -4);
        }

        $queryString = "SELECT * FROM `" . $this->table . "` WHERE " . $queryConditionString . " ORDER BY " . $order . " LIMIT :skip, :range";
        $query = $this->pdo->prepare($queryString);
        foreach ($queryConditions as $field => $value) {
            if (is_int($value)) $query->bindValue($field, $value, \PDO::PARAM_INT);
            elseif (is_bool($value)) $query->bindValue($field, $value, \PDO::PARAM_BOOL);
            elseif($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
                $query->bindValue($field, $value, \PDO::PARAM_STR);
            }
            else $query->bindValue($field, $value, \PDO::PARAM_STR);
            if($field === 'id') {
                break;
            }
        }
        $query->bindValue('skip', $limit['skip'], \PDO::PARAM_INT);
        $query->bindValue('range', $limit['range'], \PDO::PARAM_INT);
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = new $this->entity($row);
        }
        return isset($data) ? $data : [];
    }

    /**
     * @param $object
     * @return bool|mixed
     * @throws \Exception
     */
    public function save($object, $ignoreProperties = [])
    {
        if(method_exists($object, 'getId')) {
            if(!empty($object->getId())){
                $object = $this->update($object, $ignoreProperties);
            } else {
                $object = $this->create($object, $ignoreProperties);
            }
            return $object;
        } else {
            throw new \Exception('Class ' . get_class($this) . ' does not have method getId');
        }
        return false;
    }

}