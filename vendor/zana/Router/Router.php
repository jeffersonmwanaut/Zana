<?php namespace Zana\Router;

use Zana\Http\HttpException;
use Zana\Http\HttpRequest;
use Zana\Http\Page;
use Zana\Pattern\Singleton;
use Zana\Config\Config;
use Zana\Http\PageStack;

class Router extends Singleton
{
    protected static HttpRequest $httpRequest;
    protected static string $path;
    protected static array $routes = [];
    protected static array $namedRoutes = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        self::$httpRequest = new HttpRequest();
    }

    public static function get(string $path, $callable, string $routeName = null): Route
    {
        return self::addRoute($path, $callable, $routeName, 'GET');
    }

    public static function post(string $path, $callable, string $routeName = null): Route
    {
        return self::addRoute($path, $callable, $routeName, 'POST');
    }

    public static function patch(string $path, $callable, string $routeName = null): Route
    {
        return self::addRoute($path, $callable, $routeName, 'PATCH');
    }

    public static function put(string $path, $callable, string $routeName = null): Route
    {
        return self::addRoute($path, $callable, $routeName, 'PUT');
    }

    public static function delete(string $path, $callable, string $routeName = null): Route
    {
        return self::addRoute($path, $callable, $routeName, 'DELETE');
    }

    public static function any(string $path, $callable, string $routeName = null, array $requestMethods = ['GET']): Route
    {
        foreach ($requestMethods as $requestMethod) {
            self::addRoute($path, $callable, $routeName, $requestMethod);
        }
        return new Route($path, $callable);
    }

    public static function all(string $path, $callable, string $routeName = null): Route {
        $requestMethods = ['GET', 'POST', 'PATCH', 'PUT', 'DELETE'];
        foreach ($requestMethods as $requestMethod) {
            self::addRoute($path, $callable, $routeName, $requestMethod);
        }
        return new Route($path, $callable);
    }

    protected static function addRoute(string $path, $callable, string $routeName, string $method): Route
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

    public static function run(): Page
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
                PageStack::push(Config::get('path.url_root') . '/' . self::$path);
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
    public static function generateUrl(string $routeName, array $params = []): string
    {
        if (!isset(self::$namedRoutes[$routeName])) {
            throw new RouterException('No route matches <b>' . $routeName . '</b>', RouterException::NO_ROUTE_MATCHES);
        }
        return Config::get('path')['url_root'] . '/' . self::$namedRoutes[$routeName]->getUrl($params);
    }

    /**
     * @return Route[]
     */
    public static function routes(): array
    {
        return self::$routes;
    }

    /**
     * @return Route[]
     */
    public static function namedRoutes(): array
    {
        ksort(self::$namedRoutes);
        return self::$namedRoutes;
    }
}