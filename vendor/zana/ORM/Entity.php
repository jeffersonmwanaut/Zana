<?php namespace Zana\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        return $this->table;
    }

    private function create(ReflectionClass $class)
    {
        // Connect to the database
        $db = CONFIG['db']['mysql'];
        if (!empty($db)) {
            $pdo = new PDO('mysql:host=' . $db['host'] . '; port=' . $db['port'] . '; dbname=' . $db['name'], $db['user'], $db['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    
        // Get the table name
        $table = $this->getName();
    
        // Create the SQL query to create the table
        $query = "CREATE TABLE IF NOT EXISTS `$table` (";
    
        // Get the properties of the class
        $properties = $class->getProperties();
    
        // Loop through the properties
        foreach ($properties as $property) {
            // Get the attributes for the property
            $attributes = $property->getAttributes(Column::class);
    
            // Iterate over the attributes
            foreach ($attributes as $attribute) {
                // Create a new instance of the attribute
                $column = $attribute->newInstance();
    
                // Add the property name to the query
                $query.= "`{$column->getName()}` {$column->getType()}";
    
                // If the length is provided, add it to the query
                if ($column->getLength()) {
                    $query.= "({$column->getLength()})";
                }

                // If the property is primary key, add the appropriate flag to the query
                if ($column->isKey()) {
                    $query.= ' PRIMARY KEY';
                }
    
                // If the property is auto increment, add the appropriate flag to the query
                if ($column->isAutoincrement()) {
                    $query.= ' AUTO_INCREMENT';
                }
    
                // If the property is nullable, add the appropriate flag to the query
                if ($column->isNullable()) {
                    $query.= ' NULL';
                } else {
                    $query.= ' NOT NULL';
                }
    
                // If the property is unique, add the appropriate flag to the query
                if ($column->isUnique()) {
                    $query.= ' UNIQUE';
                }
    
                // If a default value is provided, add the appropriate flag to the query
                if ($column->getDefault() !== null) {
                    $query.= " DEFAULT {$column->getDefault()}";
                }
    
                // If a comment is provided, add the appropriate flag to the query
                if ($column->getComment() !== null) {
                    $query.= " COMMENT '{$column->getComment()}'";
                }
    
                // Add a comma to the query
                $query.= ', ';
            }
        }
    
        // Remove the last comma
        $query = substr($query, 0, -2);
    
        $query.= ")";
    
        // Execute the query
        $pdo->exec($query);
    }
}