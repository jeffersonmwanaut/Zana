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

    public function __construct()
    {
        $this->page = new Page();
        $this->httpRequest = new HttpRequest();
        $this->httpResponse = new HttpResponse();
        $this->session = new Session();

        $this->page->addVars([
            'httpRequest' => $this->httpRequest,
            'httpResponse' => $this->httpResponse,
            'session' => $this->session,
            'router' => Router::class,
            'config' => Config::class
        ]);
    }
}