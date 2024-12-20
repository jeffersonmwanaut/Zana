<?php namespace Zana\Router;

class Route
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var mixed
     */
    private $callable;
    /**
     * @var string[]
     */
    private $matches = [];
    /**
     * @var string[]
     */
    private $params = [];

    const   MAIN = '_MAIN',
            UNDER_CONSTRUCTION = '_UNDER_CONSTRUCTION',
            ERR_404 = '_404',
            NAVIGATE_BACK = '_NAVIGATE_BACK',
            UNDER_MAINTENANCE = '_UNDER_MAINTENANCE';

    /**
     * @param string $path
     * @param mixed $callable
     * @param string $callableNamespace
     */
    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function getParams()
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

    /**
     * @param string[] $matches
     * @return string
     */
    private function paramMatch($matches)
    {
        if (isset($this->params[$matches[2]])) {
            return '(' . $this->params[$matches[2]] . ')';
        }
        return '([^/]+)';
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
    public function with($param, $regex)
    {
        $regex = str_replace(['^', '$'], '', $regex);
        $this->params[$param] = str_replace('(', '(?:',$regex);
        return $this;
    }

    /**
     * @param array $params
     * @return mixed|string
     */
    public function getUrl(array $params = [])
    {
        $path = $this->path;
        foreach ($params as $param => $value){
            $path = preg_replace('#(:|{)('. $param .')(}|)?#', $value, $path);
        }
        return $path;
    }
}