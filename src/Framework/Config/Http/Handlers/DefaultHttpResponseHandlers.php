<?php

namespace FBAConsulting\Libs\Slim\Framework\Config\Http\Handlers;

use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultNotAllowedHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultNotFoundHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultPhpErrorHandler;
use Psr\Container\ContainerInterface;

/**
 * Get default handlers when ConfigCapsuleHandler is not defined
 */
class DefaultHttpResponseHandlers implements HttpResponseHandlers {

    private ContainerDecorator $container;

    /**
     * Required the container to set on handlers
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @return \Closure
     */
    public function getErrorHandler(): \Closure
    {
        return function () {
            return new DefaultErrorHandler(
                $this->container
            );
        };
    }

    /**
     * @return \Closure
     */
    public function getPhpErrorHandler(): \Closure
    {
        return function () {
            return new DefaultPhpErrorHandler(
                $this->container
            );
        };
    }

    /**
     * @return \Closure
     */
    public function getNotAllowedHandler(): \Closure
    {
        return function () {
            return new DefaultNotAllowedHandler(
                $this->container
            );
        };
    }

    /**
     * @return \Closure
     */
    public function getNotFoundHandler(): \Closure
    {
        return function () {
            return new DefaultNotFoundHandler(
                $this->container
            );
        };
    }

}