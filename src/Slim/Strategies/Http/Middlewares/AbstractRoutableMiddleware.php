<?php

namespace FBAConsulting\Libs\Slim\Strategies\Http\Middlewares;

use FBAConsulting\Libs\Slim\Strategies\Http\AbstractRoutable;
use FBAConsulting\Libs\Slim\Strategies\Http\Middleware;
use Slim\Container;

abstract class AbstractRoutableMiddleware
    // Extiende de Abstract routable para acceder a las propiedades del container
    extends AbstractRoutable
    // Obliga a implementar el método invoke tal y como lo requiere un middleware
    implements Middleware {

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

}