<?php namespace Zana;

use Zana\Pattern\Singleton;
use Zana\Http\HttpResponse;
use Zana\Config\Config;
use Zana\Router\Router;
use Zana\Router\Route;
use Zana\Session\Session;
use Exception;

/**
 * Class Application
 * Manages the application lifecycle, including session management, routing, and module loading.
 */
class Application extends Singleton
{
    /**
     * Modules of the application.
     * @var array
     */
    protected static array $modules = [];

    public function __construct()
    {
        Session::start();
        Router::getInstance();

        foreach (Config::get('modules') as $module) {
            $module = preg_replace('#/#', '\\', $module);
            if ($module[0] !== '\\') {
                $module = '\\' . $module;
            }
            self::$modules[] = new $module();
        }    
    }

    /**
     * Runs the application, handling routing and HTTP responses.
     *
     * @return void
     */
    public static function run(): void
    {
        $httpResponse = new HttpResponse();
        try {
            $page = Router::run();
            $httpResponse->setPage($page);
            $httpResponse->send();
        } catch (Exception $e) {
            if (Config::mode() !== 'prod') {
                echo $e; // In development mode, display the exception
            } else {
                $httpResponse->redirect(Router::generateUrl(Route::ERR_404), 404);
            }
        }
    }
}