<?php namespace Zana\Device;

class Browser extends Device
{
    protected $name;
    protected $version;
    protected $createdBy;

    public function __construct()
    {
        parent::__construct();
        $browserInfo = get_browser(null, true);
        $this->setName($browserInfo);
        $this->setVersion($browserInfo);
        $this->setCreatedBy($browserInfo);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    protected function setName(array $browserInfo)
    {
        $this->name = $browserInfo['browser'];
    }

    protected function setVersion(array $browserInfo)
    {
        $this->version = $browserInfo['version'];
    }

    protected function setCreatedBy(array $browserInfo)
    {
        $this->createdBy = $browserInfo['browser_maker'];
    }
}