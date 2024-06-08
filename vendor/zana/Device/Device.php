<?php namespace Zana\Device;

class Device
{
    protected $deviceType;
    protected $platform;
    protected $OS;
    protected $IP;

    public function __construct()
    {
        $browserInfo = get_browser(null, true);
        $this->setDeviceType($browserInfo);
        $this->setPlatform($browserInfo);
        $this->setOS();
        $this->setIP();
    }

    public function getDeviceType()
    {
        return $this->deviceType;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getOS()
    {
        return $this->OS;
    }

    public function getIP()
    {
        return $this->IP;
    }

    protected function setDeviceType(array $browserInfo)
    {
        $this->deviceType = $browserInfo['device_type'];
    }

    protected function setPlatform(array $browserInfo)
    {
        switch($browserInfo['platform']){
            case 'Win10': $this->platform = 'Windows 10'; break;
            default: $this->platform = $browserInfo['platform'];
        }
    }

    protected function setOS()
    {
        $this->OS = php_uname();
    }

    protected function setIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $this->IP = $ip;
    }
}