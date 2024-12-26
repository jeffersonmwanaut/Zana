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
        if ($browserInfo === false) {
            // Handle the case where browser info is not available
            $this->deviceType = 'unknown';
            $this->platform = 'unknown';
        } else {
            $this->setDeviceType($browserInfo);
            $this->setPlatform($browserInfo);
        }
        $this->setOS();
        $this->setIP();
    }

    public function getDeviceType(): string
    {
        return $this->deviceType;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function getOS(): string
    {
        return $this->OS;
    }

    public function getIP(): string
    {
        return $this->IP;
    }

    protected function setDeviceType(array $browserInfo): void
    {
        $this->deviceType = $browserInfo['device_type'] ?? 'unknown';
    }

    protected function setPlatform(array $browserInfo): void
    {
        $platformMap = [
            'Win10' => 'Windows 10',
            'Win' => 'Windows',
            'Mac' => 'macOS',
            'Linux' => 'Linux',
            // Add more mappings as needed
        ];

        $this->platform = $platformMap[$browserInfo['platform']] ?? $browserInfo['platform'] ?? 'unknown';
    }

    protected function setOS()
    {
        $this->OS = php_uname();
    }

    protected function setIP(): void
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // Validate IP format
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->IP = $ip;
        } else {
            $this->IP = 'unknown';
        }
    }
}