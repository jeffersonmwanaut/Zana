<?php namespace Zana;

use Zana\Pattern\Singleton;
use Zana\Http\HttpResponse;

/**
 * Create by: Jefferson Mwanaut
 * Last modified: 2012-12-11 00:58
 */
class Application extends Singleton
{
    /**
     * Modules of the application.
     * @var string[]
     */
    protected static $modules = [];

    public function __construct()
    {
        foreach (\Zana\Config\Config::get('modules') as $module)
        {
            self::$modules[] = new $module();
        }   
    }

    public static function run()
    {
        try {
            $page = \Zana\Router\Router::run();
            (new HttpResponse($page))->send();
        } catch (Exception $e) {
            echo $e;
        }
    }
}