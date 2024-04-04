<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use Closure;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotAllowedHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotFoundHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultPhpErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Decorators\ContainerDecorator;

/**
 * Get default handlers when ConfigCapsuleHandler is not defined
 */
class DefaultConfigCapsuleHandlers implements ConfigCapsuleHandlers {

    /**
     * @var ContainerDecorator
     */
    private $container;

    /**
     * Required the container to set on handlers
     * @param ContainerDecorator $container
     */
    public function __construct(ContainerDecorator $container) {
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