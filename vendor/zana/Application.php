<?php namespace Zana;

use Zana\Pattern\Singleton;
use Zana\Http\HttpResponse;
use Zana\Config\Config;
use Zana\Router\Router;

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
        foreach (Config::get('modules') as $module)
        {
            $module = preg_replace('#/#', '\\', $module);
            if($module[0] !== '\\') $module = '\\' . $module;
            self::$modules[] = new $module();
        }    
    }

    public static function run()
    {
        $httpResponse = new HttpResponse();
        try {
            $page = Router::run();
            $httpResponse->setPage($page);
            $httpResponse->send();
        } catch (Exception $e) {
            if(Config::mode() !== 'prod') {
                echo $e;
            } else {
                $httpResponse->redirect(Router::generateUrl('_404'), $statusCode = 404);
            }
        }
    }
}
