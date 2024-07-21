<?php namespace Main;

use Zana\Module;
use Zana\Router\Router;

class Main extends Module
{
    public function __construct()
    {
        // Your first route
        Router::get(
            '/', // url path
            'Main\Controller\MainController#index', // Controller + action
            '_MAIN' // route name
        );

        Router::get(
            '/404',
            'Main\Controller\MainController#error404',
            '_404'
        );

        Router::get(
            '/under-construction',
            'Main\Controller\MainController#underConstruction',
            '_UNDER_CONSTRUCTION'
        );
    }
}