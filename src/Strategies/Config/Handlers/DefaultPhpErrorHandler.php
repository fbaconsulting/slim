<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\PhpErrorHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultPhpErrorHandler implements PhpErrorHandler {

    public function __invoke(Request $request, Response $response, $error) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    }

}