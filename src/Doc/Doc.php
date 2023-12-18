<?php namespace Doc;

use Zana\Module;
use Zana\Router\Router;

class Doc extends Module
{
    public function __construct()
    {
        Router::get(
            '/doc', // url path
            'Doc\Controller\DocController#index', // Controller + action
            '_DOC' // route name
        );

        Router::get(
            '/download',
            'Doc\Controller\DocController#download',
            '_DOWNLOAD'
        );

        Router::get(
            '/structure',
            'Doc\Controller\DocController#structure',
            '_STRUCTURE'
        );

        Router::get(
            '/example',
            'Doc\Controller\DocController#example',
            '_EXAMPLE'
        );
    }
}