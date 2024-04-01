<?php

namespace FBAConsulting\Libs\Slim\Strategies\Decorators;

use Slim\Routable;

class RoutableDecorator extends Routable
{

    /**
     * Original routable can't identify the http method without the Route
     * @var string
     */
    private $routeMethod;

    public function __construct($routePattern, $routeCallable, $routeMethod) {

        parent::__construct($routePattern, $routeCallable);

        // The routable method is extended property of decorator
        $this->routeMethod = $routeMethod;

    }

    /**
     * @return string
     */
    public function getRouteMethod()
    {
        return $this->routeMethod;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

}