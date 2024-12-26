<?php namespace Zana\Http;

use Zana\File\File;
use Zana\Image\Image;
use Zana\Config\Config;

/**
 * Class HttpRequest
 * @package Zana\Http
 */
class HttpRequest
{
    private string $urlRoot;

    public function __construct()
    {
        $this->urlRoot = Config::get('path.url_root');
    }

     /**
     * Get a value from the GET parameters.
     * @param string|null $key
     * @return mixed|bool
     */
    public function get(?string $key = null)
    {
        if (is_null($key)) {
            return !empty($_GET) ? $_GET : false;
        }
        return isset($_GET[$key]) ? (is_string($_GET[$key]) ? trim($_GET[$key]) : $_GET[$key]) : false;
    }

    /**
     * Get a value from the POST parameters.
     * @param string|null $key
     * @return mixed|bool
     */
    public function post(?string $key = null)
    {
        if (is_null($key)) {
            return !empty($_POST) ? $_POST : false;
        }
        return isset($_POST[$key]) ? (is_string($_POST[$key]) ? trim($_POST[$key]) : $_POST[$key]) : false;
    }

    /**
     * Check if a key exists in either GET or POST parameters.
     * @param string $key
     * @return bool
     */
    public function submit(string $key): bool
    {
        return isset($_POST[$key]) || isset($_GET[$key]);
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
     * Check if a file is uploaded without errors.
     * @param string $key
     * @return bool
     */
    private function isFileUploaded(string $key): bool
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] === 0;
    }

    /**
     * @param string $key
     * @return bool|File
     */
    public function file($key)
    {
        return $this->isFileUploaded($key) ? new File($_FILES[$key]) : false;
    }

    /**
     * @param string $key
     * @return bool|Image
     */
    public function image($key)
    {
        return $this->isFileUploaded($key) ? new Image($_FILES[$key]) : false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$_SERVER['REQUEST_URI'];
    }

    /**
     * Get the HTTP request method.
     * @return string
     */
    public function requestMethod(): string
    {
        return $this->get('_METHOD') ?: ($this->post('_METHOD') ?: $_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the request URI without the base URL.
     * @return string
     */
    public function requestUri(): string
    {
        $params = explode('?', $_SERVER['REQUEST_URI']);
        return $this->get('url') . (isset($params[1]) ? '?' . $params[1] : '');
    }

    /**
     * Get the full request URL.
     * @return string
     */
    public function requestUrl(): string
    {
        $params = explode('?', $_SERVER['REQUEST_URI']);
        return $this->urlRoot . '/' . $this->get('url') . (isset($params[1]) ? '?' . $params[1] : '');
    }

     /**
     * Get the referrer URL.
     * @return string
     */
    public function referrer(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '#';
    }

     /**
     * Generate a URL for navigating back.
     * @return string
     */
    public function back(): string
    {
        return \Zana\Router\Router::generateUrl('_NAVIGATE_BACK');
    }

    /**
     * Check if the request is an AJAX request.
     * @return bool
     */
    public function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

}