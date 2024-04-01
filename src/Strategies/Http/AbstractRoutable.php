<?php

namespace FBAConsulting\Libs\Slim\Strategies\Http;

use Slim\Container;
use Slim\Router;

/**
 * Clase común de la que extienden los elementos que consideramos "Routables": Controllers y Middlewares
 */
abstract class AbstractRoutable {

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->router    = $this->container->get('router');
    }

}