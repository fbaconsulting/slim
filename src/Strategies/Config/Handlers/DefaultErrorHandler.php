<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\ErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Decorators\ContainerDecorator;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultErrorHandler implements ErrorHandler {

    /**
     * @var ContainerDecorator
     */
    private $container;

    public function __construct(ContainerDecorator $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $exception) {
        return $response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    }

}