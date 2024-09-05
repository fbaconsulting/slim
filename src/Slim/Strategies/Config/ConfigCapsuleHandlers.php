<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\ErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\NotAllowedHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\NotFoundHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\PhpErrorHandler;

interface ConfigCapsuleHandlers {

    /**
     * @return ErrorHandler
     */
    function getErrorHandler();

    /**
     * @return PhpErrorHandler
     */
    function getPhpErrorHandler();

    /**
     * @return NotAllowedHandler
     */
    function getNotAllowedHandler();

    /**
     * @return NotFoundHandler
     */
    function getNotFoundHandler();

}