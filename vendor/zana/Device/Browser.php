<?php namespace Zana\Device;

class Browser extends Device
{
    protected string $name;
    protected string $version;
    protected string $createdBy;

    public function __construct()
    {
        parent::__construct();
        $browserInfo = get_browser(null, true);
        
        if ($browserInfo === false) {
            // Handle the case where browser info is not available
            $this->name = 'unknown';
            $this->version = 'unknown';
            $this->createdBy = 'unknown';
        } else {
            $this->setName($browserInfo);
            $this->setVersion($browserInfo);
            $this->setCreatedBy($browserInfo);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    protected function setName(array $browserInfo): void
    {
        $this->name = $browserInfo['browser'] ?? 'unknown';
    }

    protected function setVersion(array $browserInfo): void
    {
        $this->version = $browserInfo['version'] ?? 'unknown';
    }

    protected function setCreatedBy(array $browserInfo): void
    {
        $this->createdBy = $browserInfo['browser_maker'] ?? 'unknown';
    }
}