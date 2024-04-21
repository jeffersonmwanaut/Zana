<?php namespace Zana\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ManyToMany
{
    private string $targetEntity;
    private string $mappedBy;
    private string $joinTable;
    private string $joinColumns;
    private string $inverseJoinColumns;

    public function __construct(string $targetEntity, string $mappedBy = '', string $joinTable = '', string $joinColumns = '', string $inverseJoinColumns = '') 
    {
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
        if ($joinTable) {
            $this->joinTable = $joinTable;
        }
        if ($joinColumns) {
            $this->joinColumns = $joinColumns;
        }
        if ($inverseJoinColumns) {
            $this->inverseJoinColumns = $inverseJoinColumns;
        }
    }

    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    public function getMappedBy()
    {
        return $this->mappedBy;
    }

    public function getJoinTable()
    {
        return $this->joinTable;
    }

    public function getJoinColumns()
    {
        return $this->joinColumns;
    }

    public function getInverseJoinColumns()
    {
        return $this->inverseJoinColumns;
    }
}