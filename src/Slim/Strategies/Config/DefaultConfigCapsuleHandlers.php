<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use Closure;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotAllowedHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotFoundHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultPhpErrorHandler;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use Psr\Container\ContainerInterface;

/**
 * Get default handlers when ConfigCapsuleHandler is not defined
 */
class DefaultConfigCapsuleHandlers implements ConfigCapsuleHandlers {

    private ContainerDecorator $container;

    /**
     * Required the container to set on handlers
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @return Closure
     */
    public function getErrorHandler() {
        return function () {
            return new DefaultErrorHandler(
                $this->container
            );
        };
    }

    /**
     * @return Closure
     */
    public function getPhpErrorHandler() {
        return function () {
            return new DefaultPhpErrorHandler(
                $this->container
            );
        };
    }

    /**
     * @return Closure
     */
    public function getNotAllowedHandler() {
        return function () {
            return new DefaultNotAllowedHandler(
                $this->container
            );
        };
    }

    /**
     * @return Closure
     */
    public function getNotFoundHandler() {
        return function () {
            return new DefaultNotFoundHandler(
                $this->container
            );
        };
    }

}