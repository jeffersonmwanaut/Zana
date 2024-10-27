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
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = []; // Initialize the flash array if it doesn't exist
        }
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash($key)
    {
        self::start();
        
        // Check if the flash array exists and the key is set
        if (isset($_SESSION['flash']) && array_key_exists($key, $_SESSION['flash'])) {
            $flash = $_SESSION['flash'][$key]; // Retrieve the flash message
            unset($_SESSION['flash'][$key]); // Remove it from the session
            return $flash; // Return the message
        }
        
        return false; // Return false if the flash message does not exist
    }

    public static function getFlashes()
    {
        self::start();
        
        // Check if the flash array exists
        if (isset($_SESSION['flash'])) {
            $flashes = $_SESSION['flash']; // Retrieve all flash messages
            unset($_SESSION['flash']); // Clear all flash messages from the session
            return $flashes; // Return the array of flash messages
        }
        
        return []; // Return an empty array if no flash messages are set
    }

    /**
     * Test if session flash is set
     * @return bool
     */
    public static function hasFlashes()
    {
        self::start();
        return isset($_SESSION['flash']) && !empty($_SESSION['flash']);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['flash']) && array_key_exists($key, $_SESSION['flash']);
    }
}