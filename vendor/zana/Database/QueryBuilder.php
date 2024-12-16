<?php namespace Zana\Database;

class QueryBuilder
{
    protected string $table;
    protected array $fields = [];
    protected array $conditions = [];
    protected array $orderBy = [];
    protected array $limit;
    protected string $queryType;
    protected int $dbType;
    protected array $joins = [];

    public function __construct($table, $dbType)
    {
        $this->table = $table;
        $this->dbType = $dbType;
    }

    protected function quoteIdentifier($identifier)
    {
        return $this->dbType === DbType::POSTGRESQL || $this->dbType === DbType::SQLITE ? "\"$identifier\"" : "`$identifier`";
    }

    public function select($fields = ['*'])
    {
        $this->queryType = 'SELECT';
        $this->fields = $fields;
        return $this;
    }

    public function insert($data)
    {
        $this->queryType = 'INSERT';
        $this->fields = $data;
        return $this;
    }

    public function update($data)
    {
        $this->queryType = 'UPDATE';
        $this->fields = $data;
        return $this;
    }

    public function delete()
    {
        $this->queryType = 'DELETE';
        return $this;
    }

    public function join($table, $firstCondition, $operator = '=', $secondCondition)
    {
        $this->joins[] = [
            'type' => 'INNER', // Default join type
            'table' => $table,
            'firstCondition' => $firstCondition,
            'operator' => $operator,
            'secondCondition' => $secondCondition
        ];
        return $this;
    }

    public function leftJoin($table, $firstCondition, $operator = '=', $secondCondition)
    {
        $this->joins[] = [
            'type' => 'LEFT',
            'table' => $table,
            'firstCondition' => $firstCondition,
            'operator' => $operator,
            'secondCondition' => $secondCondition
        ];
        return $this;
    }

    public function rightJoin($table, $firstCondition, $operator = '=', $secondCondition)
    {
        $this->joins[] = [
            'type' => 'RIGHT',
            'table' => $table,
            'firstCondition' => $firstCondition,
            'operator' => $operator,
            'secondCondition' => $secondCondition
        ];
        return $this;
    }

    public function where($condition, $value)
    {
        // Check if the value is an array
        if (is_array($value)) {
            // Create a condition for each value in the array
            $placeholders = implode(", ", array_map(fn($v) => is_string($v) && strpos($v, ':') === 0 ? $v : '?', $value));
            $this->conditions[] = [$condition, "IN ($placeholders)", $value]; // Store the array values for later binding
        } else {
            // Check if the value is a named parameter
            if (is_string($value) && strpos($value, ':') === 0) {
                // Named parameter
                $this->conditions[] = [$condition, $value];
            } else {
                // Indexed parameter
                $this->conditions[] = [$condition, '?'];
            }
        }
        return $this;
    }

    public function orderBy($field, $direction = 'ASC')
    {
        $this->orderBy[] = [$field, $direction];
        return $this;
    }

    public function limit($offset, $range)
    {
        $this->limit = [$offset, $range];
        return $this;
    }

    protected function buildSelect()
    {
        $fields = implode(", ", array_map(fn($field) => $this->quoteIdentifier($field), $this->fields));
        $query = "SELECT $fields FROM " . $this->quoteIdentifier($this->table);

        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $query .= " " . $join['type'] . " JOIN " . $this->quoteIdentifier($join['table']) . 
                          " ON " . $this->quoteIdentifier($join[' firstCondition']) . " " . $join['operator'] . " " . $this->quoteIdentifier($join['secondCondition']);
            }
        }

        if (!empty($this->conditions)) {
            $query .= " WHERE " . implode(" AND ", array_map(fn($c) => $this->quoteIdentifier($c[0]) . " = " . $c[1], $this->conditions));
        }

        if (!empty($this->orderBy)) {
            $orderBy = implode(", ", array_map(fn($o) => $this->quoteIdentifier($o[0]) . " {$o[1]}", $this->orderBy));
            $query .= " ORDER BY $orderBy";
        }

        if ($this->limit) {
            $query .= " LIMIT {$this->limit[1]} OFFSET {$this->limit[0]}";
        }

        return $query;
    }

    protected function buildInsert()
    {
        $fields = implode(", ", array_map(fn($key) => $this->quoteIdentifier($key), array_keys($this->fields)));
        $placeholders = implode(", ", array_fill(0, count($this->fields), '?'));
        return "INSERT INTO " . $this->quoteIdentifier($this->table) . " ($fields) VALUES ($placeholders)";
    }

    protected function buildUpdate()
    {
        $set = implode(", ", array_map(fn($key) => $this->quoteIdentifier($key) . " = ?", array_keys($this->fields)));
        $query = "UPDATE " . $this->quoteIdentifier($this->table) . " SET $set";

        if (!empty($this->conditions)) {
            $query .= " WHERE " . implode(" AND ", array_map(fn($c) => $this->quoteIdentifier($c[0]) . " = " . $c[1], $this->conditions));
        }

        return $query;
    }

    protected function buildDelete()
    {
        $query = "DELETE FROM " . $this->quoteIdentifier($this->table);

        if (!empty($this->conditions)) {
            $query .= " WHERE " . implode(" AND ", array_map(fn($c) => $this->quoteIdentifier($c[0]) . " = " . $c[1], $this->conditions));
        }

        return $query;
    }

    public function build()
    {
        switch ($this->queryType) {
            case 'SELECT':
                return $this->buildSelect();
            case 'INSERT':
                return $this->buildInsert();
            case 'UPDATE':
                return $this->buildUpdate();
            case 'DELETE':
                return $this->buildDelete();
            default:
                throw new \Exception("Query type not set.");
        }
    }
}