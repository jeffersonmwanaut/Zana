<?php namespace Zana\Router;

class UrlBuilder {
    private $route;
    private $routeParameters = [];
    private $queryStringParameters = [];

    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    public function setRouteParameter($key, $value) {
        $this->routeParameters[$key] = $value;
        return $this;
    }

    public function setQueryStringParameter($key, $value) {
        $this->queryStringParameters[$key] = $value;
        return $this;
    }

    public function build() {
        $url = Router::generateUrl($this->route, $this->routeParameters);
        if (!empty($this->queryStringParameters)) {
            $url.= '?'. http_build_query($this->queryStringParameters);
        }
        return $url;
    }
}
