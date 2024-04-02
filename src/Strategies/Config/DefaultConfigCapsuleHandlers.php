<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotAllowedHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotFoundHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultPhpErrorHandler;
use FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\ErrorHandler;
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
     * @return \FBAConsulting\Libs\Slim\Strategies\Config\Handlers\Interfaces\ErrorHandler
     */
    public function getErrorHandler() {
        return new DefaultErrorHandler($this->container);
    }

    /**
     * @return \FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultPhpErrorHandler
     */
    public function getPhpErrorHandler() {
        return new DefaultPhpErrorHandler($this->container);
    }

    /**
     * @return \FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultNotAllowedHandler
     */
    public function getNotAllowedHandler() {
        return new DefaultNotAllowedHandler($this->container);
    }

    /**
     * @return DefaultNotFoundHandler
     */
    public function getNotFoundHandler() {
        return new DefaultNotFoundHandler($this->container);
    }

}