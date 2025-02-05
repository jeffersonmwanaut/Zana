<?php namespace Zana\Router;

class Route
{
    private string $path;
    private $callable;
    private array $matches = [];
    private array $params = [];

    const MAIN = '_MAIN';
    const UNDER_CONSTRUCTION = '_UNDER_CONSTRUCTION';
    const ERR_404 = '_404';
    const NAVIGATE_BACK = '_NAVIGATE_BACK';
    const UNDER_MAINTENANCE = '_UNDER_MAINTENANCE';

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $callableNamespace
     */
    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param string $url
     * @return bool
     */
    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#(:|{)([\w]+)(}|)?#', [$this, 'paramMatch'], $this->path);
        $regex = '#^' . $path . '$#';
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch(array $matches): string
    {
        $paramName = $matches[2];
        $regex = '([^/]+)'; // Default regex for parameters

        // Check if a custom regex is defined for this parameter
        if (isset($this->params[$paramName])) {
            $regex = '(' . $this->params[$paramName] . ')';
        }

        // Check if the parameter is defined as optional in the path
        if (strpos($this->path, '{' . $paramName . '?}') !== false) {
            return "($regex)?"; // Make it optional
        }

        return $regex;
    }

    /**
     * @return mixed
     */
    public function call()
    {
        if(is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controller = new  $params[0]();
            $action = $params[1];
            if(!method_exists($controller, $action)) {
                throw new RouterException('Action <b>' . $action . '</b> not found in ' . get_class($controller), RouterException::ACTION_NOT_FOUND);
            }
            return call_user_func_array([$controller, $action], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    /**
     * @param string $param
     * @param string $regex
     * @return $this
     */
    public function with(string $param, string $regex): self
    {
        $regex = str_replace(['^', '$'], '', $regex);
        $this->params[$param] = str_replace('(', '(?:',$regex);
        return $this;
    }

    /**
     * @param array $params
     * @return mixed|string
     */
    public function getUrl(array $params = []): string
    {
        $path = $this->path;
        foreach ($params as $param => $value){
            $path = preg_replace('#(:|{)('. $param .')(}|)?#', $value, $path);
        }
        return $path;
    }
}