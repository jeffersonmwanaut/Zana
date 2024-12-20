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
        UNKNOWN_PAGE_ERR = 30000,
        LAYOUT_N_VIEW_NOT_FOUND = 30001,
        LAYOUT_NOT_FOUND = 30002,
        VIEW_NOT_FOUND = 30003,
        INVALID_TEMPLATE = 30004,
        INVALID_VIEW = 30005,
        INVALID_VAR_NAME = 30006;
}
