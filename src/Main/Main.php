<?php namespace Main;

use Zana\Module;
use Zana\Router\Router;
use Zana\Router\Route;

class Main extends Module
{
    public function __construct()
    {
        // Your first route
        Router::get(
            '/', // url path
            'Main\Controller\MainController#index', // Controller + action
            Route::MAIN // route name
        );

        Router::get(
            '/404',
            'Main\Controller\MainController#error404',
            Route::ERR_404
        );

        Router::get(
            '/under-construction',
            'Main\Controller\MainController#underConstruction',
            Route::UNDER_CONSTRUCTION
        );

        Router::get(
            '/navigate-back',
            'Main\Controller\MainController#navigateBack',
            Route::NAVIGATE_BACK
        );
    }
}