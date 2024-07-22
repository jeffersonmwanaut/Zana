<?php namespace Zana\Router;

class UrlBuilder {
    private $route;
    private $routeParameters = [];
    private $queryStringParameters = [];
    private $anchor;
    private $requiresAuthentication = false;
    private $requiresAuthorization = false;

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

    public function setAnchor($anchor) {
        $this->anchor = $anchor;
        return $this;
    }

    public function requiresAuthentication($value = true) {
        $this->requiresAuthentication = $value;
        return $this;
    }

    public function requiresAuthorization($value = true) {
        $this->requiresAuthorization = $value;
        return $this;
    }

    public function build() {
        $url = Router::generateUrl($this->route, $this->routeParameters);
        if (!empty($this->queryStringParameters)) {
            $url .= '?' . http_build_query($this->queryStringParameters);
        }
        if (!empty($this->anchor)) {
            $url .= '#' . $this->anchor;
        }
        if ($this->requiresAuthentication) {
            // Add authentication token or login URL to the URL
            // For example:
            // $url .= '?auth_token=' . Auth::getToken();
        }
        if ($this->requiresAuthorization) {
            // Add authorization token or permission check to the URL
            // For example:
            // $url .= '?permission=' . Permission::CHECK_ADMIN;
        }
        return $url;
    }
}
