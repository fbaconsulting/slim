<?php

namespace FBAConsulting\Libs\Slim\Strategies\Http\Controllers;

use FBAConsulting\Libs\Slim\Strategies\Http\AbstractRoutable;
use FBAConsulting\Libs\Slim\Strategies\Http\Controller;
use Slim\Container;

abstract class AbstractRoutableController
    // Extiende de Abstract routable para acceder a las propiedades del container
    extends AbstractRoutable
    // Obliga a implementar el método invoke tal y como lo requiere un controlador
    implements Controller {

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

}