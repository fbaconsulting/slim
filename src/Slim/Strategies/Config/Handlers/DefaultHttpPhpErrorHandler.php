<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config\Handlers;

use FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers\Interfaces\HttpPhpErrorHandler;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use Slim\Http\Request;
use Slim\Http\Response;

class DefaultHttpPhpErrorHandler implements HttpPhpErrorHandler {

    /**
     * @var ContainerDecorator
     */
    private $container;

    public function __construct(ContainerDecorator $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, $error) {

        $errorMessage = $this->container->isEnableDisplayError() ? sprintf('Something went wrong: %s!', $error)
            : 'Something went wrong'
        ;

        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write($errorMessage);
    }

}