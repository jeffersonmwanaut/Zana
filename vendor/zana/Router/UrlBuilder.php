<?php namespace Zana\Router;

class UrlBuilder {
    private $route;
    private $parameters = [];

    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    public function setParameter($key, $value) {
        $this->parameters[$key] = $value;
        return $this;
    }

    public function build() {
        $url = Router::generateUrl($this->route);
        if (!empty($this->parameters)) {
            $url .= '?' . http_build_query($this->parameters);
        }
        return $url;
    }
}
