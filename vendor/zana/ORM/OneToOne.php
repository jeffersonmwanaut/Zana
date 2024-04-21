<?php namespace Zana\ORM;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class OneToOne
{
    private string $targetEntity;
    private string $mappedBy;

    public function __construct(string $targetEntity, string $mappedBy = '') 
    {
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
    }

    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    public function getMappedBy()
    {
        return $this->mappedBy;
    }
}