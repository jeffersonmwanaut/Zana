<?php namespace Zana\Router;

class RouterException extends \Zana\Exception
{
    const UNKNOWN_ROUTER_ERR = 10000,
          NO_ROUTE_FOUND = 10001,
          NO_ROUTE_MATCHES = 10002,
          ACTION_NOT_FOUND = 10003;
}