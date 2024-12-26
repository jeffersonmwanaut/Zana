<?php
namespace Zana\Http;

/**
 * Class HttpResponse
 * @package Zana\Http
 */
class HttpResponse
{
    /**
     * @var Page|null
     */
    protected ?Page $page;

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
    public function addHeader($header): void
    {
        header($header);
    }

    /**
     * Redirect the client to a specified location.
     * @param string $location
     * @param bool $replace
     * @param int|null $statusCode
     * @return void
     * @throws HttpException
     */
    public function redirect(string $location, bool $replace = true, ?int $statusCode = null): void
    {
        if (!filter_var($location, FILTER_VALIDATE_URL)) {
            throw new HttpException("Invalid redirect location", HttpException::INVALID_REDIRECT);
        }
        header('Location: ' . $location, $replace, $statusCode ?? 302);
        exit();
    }

    /**
     * @throws \Exception
     */
    public function send()
    {
        if (!is_null($this->page)) {
            exit($this->page->getGeneratedPage());
        } else {
            throw new HttpException("Invalid view", HttpException::UNKNOWN_PAGE_ERR);
        }
    }

    /**
     * @param Page $page
     */
    public function setPage(Page $page): void
    {
        $this->page = $page;
    }
}
