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
    public function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        $this->start();
        return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : false;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function delete($key)
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    public function setFlash($key, $message)
    {
        $this->start();
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash($key)
    {
        $this->start();
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

    public function getFlashes()
    {
        $this->start();
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Test if session flash is set
     * @return bool
     */
    public function hasFlashes()
    {
        $this->start();
        return isset($_SESSION['flash']);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasFlash($key)
    {
        $this->start();
        return isset($_SESSION['flash'][$key]);
    }
}