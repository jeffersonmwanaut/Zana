<?php namespace Zana\Router;

class UrlBuilder {
    private string $route;
    private array $routeParameters = [];
    private array $queryStringParameters = [];
    private ?string $anchor = null;

    /**
     * Set the route for the URL.
     *
     * @param string $route The route to set.
     * @return $this
     */
    public function setRoute(string $route): self {
        $this->route = $route;
        return $this;
    }

    /**
     * Set a route parameter.
     *
     * @param string $key The parameter key.
     * @param mixed $value The parameter value.
     * @return $this
     */
    public function setRouteParameter(string $key, $value): self {
        $this->routeParameters[$key] = $value;
        return $this;
    }

    /**
     * Set a query string parameter.
     *
     * @param string $key The parameter key.
     * @param mixed $value The parameter value.
     * @return $this
     */
    public function setQueryStringParameter(string $key, $value): self {
        $this->queryStringParameters[$key] = $value;
        return $this;
    }

    /**
     * Set the anchor for the URL.
     *
     * @param string $anchor The anchor to set.
     * @return $this
     */
    public function setAnchor(string $anchor): self {
        $this->anchor = $anchor;
        return $this;
    }

    /**
     * Build the final URL.
     *
     * @return string The constructed URL.
     */
    public function build(): string {
        $url = Router::generateUrl($this->route, $this->routeParameters);
        
        if (!empty($this->queryStringParameters)) {
            $url .= '?' . http_build_query($this->queryStringParameters);
        }
        
        if (!empty($this->anchor)) {
            $url .= '#' . $this->anchor;
        }
        
        return $url;
    }
}
