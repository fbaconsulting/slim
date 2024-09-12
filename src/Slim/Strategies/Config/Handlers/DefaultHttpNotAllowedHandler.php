<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers\Interfaces\HttpNotAllowedHandler;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultHttpNotAllowedHandler implements HttpNotAllowedHandler {

    /**
     * @var ContainerDecorator
     */
    private ContainerDecorator $container;

    public function __construct(ContainerDecorator $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, array $methods) {
        return $response->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write('Method must be one of: ' . implode(', ', $methods));
    }

}