<?php

namespace FBAConsulting\Libs\Slim\Exceptions\Routing;

class RouteControlReturnException extends \Exception {

    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}