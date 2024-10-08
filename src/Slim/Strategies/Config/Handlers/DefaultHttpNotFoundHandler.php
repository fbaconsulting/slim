<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers\Interfaces\HttpNotFoundHandler;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultHttpNotFoundHandler implements HttpNotFoundHandler {

    /**
     * @var ContainerDecorator
     */
    private $container;

    public function __construct(ContainerDecorator $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response) {
        return $response->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->write('Page not found');
    }

}