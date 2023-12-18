<?php namespace Zana\Pattern;

/**
 * Class Singleton
 * Created by: Jefferson Mwanaut
 * Last modified: 2022-12-10 12:06
 * @package Zana\Pattern
 */
abstract class Singleton
{
    /**
     * @var Singleton
     */
    protected static $instance = null;

    final protected function __clone(){} #restrict clone

    /**
     * @return static
     */
    public static function getInstance($params = null)
    {
        if(!static::$instance instanceof static){
            static::$instance = new static($params);
        }
        return static::$instance;
    }

}