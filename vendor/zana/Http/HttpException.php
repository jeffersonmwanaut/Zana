<?php
namespace Zana\Http;

use Zana\Exception;

/**
 * Class HttpException
 * @package Zana\Http
 */
class HttpException extends Exception
{
    const UNKNOWN_HTTP_ERR = 20000,
        REQUEST_METHOD_NOT_FOUND = 20001,
        INVALID_REDIRECT = 20002,
        UNKNOWN_PAGE_ERR = 30000,
        TEMPLATE_N_VIEW_NOT_FOUND = 30001,
        TEMPLATE_NOT_FOUND = 30002,
        VIEW_NOT_FOUND = 30003,
        INVALID_TEMPLATE = 30004,
        INVALID_VIEW = 30005,
        INVALID_VAR_NAME = 30006;
}
