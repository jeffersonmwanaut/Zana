<?php namespace Zana\Cookie;

use Exception;

/**
 * Class Cookie
 * @package Zana\Cookie
 */
class Cookie
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $domain;

    public function __construct($path = "", $domain = "")
    {
        $this->path = $path != "" ? $path : $_SERVER['SERVER_NAME'];
        $this->domain = $domain != "" ? $domain : $_SERVER['SERVER_NAME'];
    }

    /**
     * Set a cookie
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function set($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = false, $httpOnly = false)
    {
        return setCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * Get a cookie
     * @param string $name
     * @return bool
     */
    public function get($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    /**
     * Delete a cookie
     * @param string $name
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return bool
     */
    public function delete($name, $path, $domain, $secure = false, $httpOnly = false)
    {
        if(isset($_COOKIE[$name])){
            return setcookie($name, '', - time(), $path, $domain, $secure, $httpOnly);
        } else {
            return false;
        }
    }

    /**
     * @param $path
     * @throws Exception
     */
    public function setPath($path)
    {
        if (file_exists($path))
            $this->path = $path;
        else
            throw new Exception("The file could not be found " . get_class($this) . ' on line ' . __LINE__);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
}