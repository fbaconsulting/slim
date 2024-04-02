<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\NotFoundHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultNotFoundHandler implements NotFoundHandler {

    public function __invoke(Request $request, Response $response) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    }

}