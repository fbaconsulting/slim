<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces;

use Slim\Http\Request;
use Slim\Http\Response;

interface PhpErrorHandler {

    function __invoke(Request $request, Response $response, $error);

}