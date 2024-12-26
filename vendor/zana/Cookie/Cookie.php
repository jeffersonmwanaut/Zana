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
    private string $path;

    /**
     * @var string
     */
    private string $domain;

    public function __construct(?string $path = null, ?string $domain = null)
    {
        $this->path = $path ?? '/'; // Default path to root
        $this->domain = $domain ?? $_SERVER['SERVER_NAME']; // Default to server name
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
    public function set(string $name, string $value = '', int $expire = 0, ?string $path = null, ?string $domain = null, bool $secure = false, bool $httpOnly = false): bool
    {
        if (empty($name)) {
            throw new Exception("Cookie name cannot be empty.");
        }
        return setcookie($name, $value, $expire, $path ?? $this->path, $domain ?? $this->domain, $secure, $httpOnly);
    }

    /**
     * Get a cookie
     * @param string $name
     * @return bool
     */
    public function get(string $name)
    {
        return $_COOKIE[$name] ?? false;
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
    public function delete(string $name, ?string $path = null, ?string $domain = null, bool $secure = false, bool $httpOnly = false): bool
    {
        if (isset($_COOKIE[$name])) {
            return setcookie($name, '', time() - 3600, $path ?? $this->path, $domain ?? $this->domain, $secure, $httpOnly);
        }
        return false;
    }

    /**
     * Set the path for the cookies
     *
     * @param string $path
     * @throws Exception
     */
    public function setPath(string $path): void
    {
        if (!empty($path)) {
            $this->path = $path;
        } else {
            throw new Exception("The path cannot be empty.");
        }
    }

    /**
     * Get the path for the cookies
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the domain for the cookies
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Set the domain for the cookies
     *
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * Set default secure and HttpOnly flags
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function setDefaultFlags(bool $secure, bool $httpOnly): void
    {
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }
}