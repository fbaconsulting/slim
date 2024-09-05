<?php

namespace FBAConsulting\Libs\Slim\Strategies\Decorators;

use Slim\Routable as AbstractRoutable;

// todo Change as decorator
class Routable extends AbstractRoutable
{

    /**
     * Original routable can't identify the http method without the Route
     * @var string
     */
    private string $routeMethod;

    /**
     * @param string $routePattern
     * @param callable $routeCallable
     * @param string $routeMethod
     */
    public function __construct(string $routePattern, callable $routeCallable, string $routeMethod) {

        // Check if pattern is build with the Slim Framework premises
        $this->checkPattern($routePattern);

        // The routable method is extended property of decorator
        $this->routeMethod = $routeMethod;

        parent::__construct($routePattern, $routeCallable);

    }

    /**
     * @return string
     */
    public function getRouteMethod(): string
    {
        return strtoupper($this->routeMethod);
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    private function checkPattern(string $pattern) {

    }

}