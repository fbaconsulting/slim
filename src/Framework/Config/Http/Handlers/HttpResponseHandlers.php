<?php

namespace FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers;

interface HttpResponseHandlers {

    /**
     * @return \Closure
     */
    function getErrorHandler(): \Closure;

    /**
     * @return \Closure
     */
    function getPhpErrorHandler(): \Closure;

    /**
     * @return \Closure
     */
    function getNotAllowedHandler(): \Closure;

    /**
     * @return \Closure
     */
    function getNotFoundHandler(): \Closure;

}