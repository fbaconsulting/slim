<?php

namespace FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers\Interfaces;

use Slim\Http\Request;
use Slim\Http\Response;

interface HttpErrorHandler {

    function __invoke(Request $request, Response $response, $exception);

}