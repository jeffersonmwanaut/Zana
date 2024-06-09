<?php namespace Zana\Router;

use Zana\Http\HttpException;
use Zana\Http\HttpRequest;
use Zana\Http\Page;
use Zana\Pattern\Singleton;
use Zana\Config\Config;

class Router extends Singleton
{
    /**
     * @var HttpRequest
     */
    protected static $httpRequest;
    /**
     * @var string
     */
    protected static $path;
    /**
     * @var Route[]
     */
    protected static $routes = [];
    /**
     * @var Route[]
     */
    protected static $namedRoutes = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        self::$httpRequest = new HttpRequest();
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function get($path, $callable, $routeName = null)
    {
        return self::addRoute($path, $callable, $routeName, 'GET');
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function post($path, $callable, $routeName = null)
    {
        return self::addRoute($path, $callable, $routeName, 'POST');
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function patch($path, $callable, $routeName = null)
    {
        return self::addRoute($path, $callable, $routeName, 'PATCH');
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function put($path, $callable, $routeName = null)
    {
        return self::addRoute($path, $callable, $routeName, 'PUT');
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function delete($path, $callable, $routeName = null)
    {
        return self::addRoute($path, $callable, $routeName, 'DELETE');
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @param array $requestMethods
     * @return Route
     */
    public static function any($path, $callable, $routeName = null, $requestMethods = ['GET'])
    {
        $route = new Route($path, $callable);
        foreach($requestMethods as $requestMethod) {
            $route = self::addRoute($path, $callable, $routeName, $requestMethod);
        }
        return $route;
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName
     * @return Route
     */
    public static function all($path, $callable, $routeName = null)
    {
        $requestMethods = ['GET','POST','PATCH','PUT','DELETE'];
        
        $route = new Route($path, $callable);
        foreach($requestMethods as $requestMethod) {
            $route = self::addRoute($path, $callable, $routeName, $requestMethod);
        }
        return $route;
    }

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $routeName 
     * @param string $method
     * @return Route
     */
    protected static function addRoute($path, $callable, $routeName, $method)
    {
        $route = new Route($path, $callable);
        self::$routes[$method][] = $route;
        if (is_string($callable) && $routeName === null) {
            $routeName = $callable;
        }
        if ($routeName) {
            self::$namedRoutes[$routeName] = $route;
        }
        return $route;
    }

    /**
     * @return Page
     * @throws RouterException
     */
    public static function run()
    {
        if (count(self::routes()) < 1) {
            throw new RouterException("No route found", RouterException::NO_ROUTE_FOUND);
        }
        self::$path = self::$httpRequest->get('url');
        /**
         * We get the request method from the request.
         * The request method can be default such as GET or POST or customized
         * to PUT or DELETE using _METHOD key through a $_GET or $_POST super global.
         */
        $requestMethod = self::$httpRequest->get('_METHOD') ? self::$httpRequest->get('_METHOD') : (self::$httpRequest->post('_METHOD') ? self::$httpRequest->post('_METHOD') : $_SERVER['REQUEST_METHOD']);
        if (!isset(self::$routes[$requestMethod])) {
            throw new HttpException("Request method <b>{$requestMethod}</b> not found", HttpException::REQUEST_METHOD_NOT_FOUND);
        }
        foreach (self::$routes[$requestMethod] as $route) {
            if ($route->match(self::$path)) {
                return $route->call();
            }
        }
        throw new RouterException('No route matches <b>' . self::$path . '</b>', RouterException::NO_ROUTE_MATCHES);
    }

    /**
     * @param string $routeName
     * @param array $params
     * @return mixed|string
     * @throws RouterException
     */
    public static function generateUrl($routeName, $params = [])
    {
        if (!isset(self::$namedRoutes[$routeName])) {
            throw new RouterException('No route matches <b>' . $routeName . '</b>', RouterException::NO_ROUTE_MATCHES);
        }
        return Config::get('path')['url_root'] . '/' . self::$namedRoutes[$routeName]->getUrl($params);
    }

    /**
     * @return Route[]
     */
    public static function routes()
    {
        return self::$routes;
    }

    /**
     * @return Route[]
     */
    public static function namedRoutes()
    {
        ksort(self::$namedRoutes);
        return self::$namedRoutes;
    }
}