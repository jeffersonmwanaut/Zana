<?php namespace Zana\Session;

/**
 * Interface SessionInterface
 * @package Zana\Session
 */
interface SessionInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public static function get($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public static function delete($key);
}