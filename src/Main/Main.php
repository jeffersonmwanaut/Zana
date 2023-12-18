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
            '/website-under-construction',
            'Main\Controller\MainController#websiteUnderConstruction',
            '_WEBSITE_UNDER_CONSTRUCTION'
        );
    }
}