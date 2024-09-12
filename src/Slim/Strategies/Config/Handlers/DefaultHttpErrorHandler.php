<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers\Interfaces\HttpErrorHandler;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultHttpErrorHandler implements HttpErrorHandler {

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