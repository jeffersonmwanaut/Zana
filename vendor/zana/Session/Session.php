<?php namespace Zana\Session;

/**
 * Class Session
 * @package Zana\Session
 */
class Session implements SessionInterface
{
    /**
     * Start Session
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @inheritDoc
     */
    public static function get($key): mixed
    {
        self::start();
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : false;
    }

    /**
     * @inheritDoc
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public static function delete($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function setFlash($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key)
    {
        self::start();
        if (!isset($_SESSION['flash'])) {
            return false; // No flash messages set
        }
        $flash = array_key_exists($key, $_SESSION['flash']) ? $_SESSION['flash'][$key] : false;
        $flashesArrayKeys = array_keys($_SESSION['flash']);
        if(count($flashesArrayKeys) > 1) {
            unset($_SESSION['flash'][$key]);
            return $flash;
        } else {
            unset($_SESSION['flash']);
            return $flash;
        }
    }

    public static function getFlashes()
    {
        self::start();
        if (!isset($_SESSION['flash'])) {
            return []; // No flash messages set
        }
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Test if session flash is set
     * @return bool
     */
    public static function hasFlashes()
    {
        self::start();
        return isset($_SESSION['flash']);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }
}