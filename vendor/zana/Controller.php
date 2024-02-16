<?php namespace Zana;

use Zana\Http\Page;
use Zana\Http\HttpRequest;
use Zana\Http\HttpResponse;
use Zana\Session\Session;
use Zana\Session\SessionInterface;
use Zana\Router\Router;
use Zana\Config\Config;
/**
 * Class Controller
 * Process user requests and use a model and a view to return a response.
 * @package Zana
 */
class Controller
{
    /**
     * web page.
     * @var Page
     */
    protected $page;
        /**
     * @var HttpRequest
     */
    protected $httpRequest;
    /**
     * @var HttpResponse
     */
    protected $httpResponse;
    /**
     * @var SessionInterface
     */
    protected $session;

    protected $router;
    protected $config;

    public function __construct()
    {
        $this->page = (new Page())->setModule(explode('\\', get_class($this))[0]);
        $this->httpRequest = new HttpRequest();
        $this->httpResponse = new HttpResponse();
        $this->session = new Session();
        $this->router = Router::class;
        $this->config = Config::class;

        $this->page->addVars([
            'httpRequest' => $this->httpRequest,
            'httpResponse' => $this->httpResponse,
            'session' => $this->session,
            'router' => $this->router,
            'config' => $this->config
        ]);
    }
}