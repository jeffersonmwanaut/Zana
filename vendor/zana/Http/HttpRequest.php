<?php namespace Zana\Http;

use Zana\File\File;
use Zana\Image\Image;

/**
 * Class HttpRequest
 * @package Zana\Http
 */
class HttpRequest
{

    /**
     * @param string $key
     * @return bool
     */
    public function get($key = null)
    {
        if(is_null($key)) return !empty($_GET) ? $_GET : false;
        if(isset($_GET[$key])){
            if(is_string($_GET[$key])) return trim($_GET[$key]);
            return $_GET[$key];
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function post($key = null)
    {
        if(is_null($key)) return !empty($_POST) ? $_POST : false;
        if(isset($_POST[$key])){
            if(is_string($_POST[$key])) return trim($_POST[$key]);
            return $_POST[$key];
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function submit($key)
    {
        return (isset($_POST[$key]) || isset($_GET[$key])) ? true : false;
    }

    /**
     * @return File[]|bool
     */
    public function files()
    {
        if(!isset($_FILES)){
            return false;
        }
        $files = [];
        foreach ($_FILES as $key => $value) {
            $files[$key] = new File($value);
        }
        return $files;
    }

    /**
     * @return Image[]|bool
     */
    public function images()
    {
        if(!isset($_FILES)){
            return false;
        }
        $images = [];
        foreach ($_FILES as $key => $value) {
            $images[$key] = new Image($value);
        }
        return $images;
    }

    /**
     * @param string $key
     * @return bool|File
     */
    public function file($key)
    {
        return (isset($_FILES[$key]) && $_FILES[$key]['error'] == 0) ? new File($_FILES[$key]) : false;
    }

    /**
     * @param string $key
     * @return bool|Image
     */
    public function image($key)
    {
        return (isset($_FILES[$key]) && $_FILES[$key]['error'] == 0) ? new Image($_FILES[$key]) : false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$_SERVER['REQUEST_URI'];
    }

    /**
     * @return bool|string
     */
    public function requestMethod()
    {
        return $this->get('_METHOD') ? $this->get('_METHOD') : ($this->post('_METHOD') ? $this->post('_METHOD') : $_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return string
     */
    public function requestUri()
    {
        $params = explode('?', $_SERVER['REQUEST_URI']);
        $params = isset($params[1]) ? '?' . $params[1] : '';
        return $this->get('url') . $params;
    }

    /**
     * @return string
     */
    public function requestUrl()
    {
        $urlRoot = \Zana\Config\Config::get('path')['url_root'];
        $params = explode('?', $_SERVER['REQUEST_URI']);
        $params = isset($params[1]) ? '?' . $params[1] : '';
        return $urlRoot . '/' . $this->get('url') . $params;
    }

    /**
     * @return string
     */
    public function referrer()
    {
        if(!isset($_SERVER['HTTP_REFERER'])) return '#';
        $urlRoot = \Zana\Config\Config::get('path')['url_root'];
        return str_replace($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $urlRoot . '/', '', $_SERVER['HTTP_REFERER']);
    }

}