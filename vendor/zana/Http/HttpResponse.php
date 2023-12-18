<?php
namespace Zana\Http;

/**
 * Class HttpResponse
 * @package Zana\Http
 */
class HttpResponse
{


    /**
     * @var Page
     */
    protected $page;

    /**
     * HttpResponse constructor.
     * @param Page|null $page
     */
    public function __construct(Page $page = null)
    {
        $this->page = $page;
    }

    /**
     * @param string $header
     */
    public function addHeader($header)
    {
        header($header);
    }

    /**
     * @param string $location
     * @param bool $replace
     * @param int $statusCode
     */
    public function redirect($location, $replace = true, $statusCode = null)
    {
        header('Location: ' . $location, $replace, $statusCode);
        exit();
    }

    /**
     * @throws \Exception
     */
    public function send()
    {
        if (!is_null($this->page)) {
            exit($this->page->getGeneratedPage());
        }
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }
}
