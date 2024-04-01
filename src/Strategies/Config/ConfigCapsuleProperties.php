<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use Exception;
use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;

/**
 * DTO to get involved settings and dependency injection properties as independent values
 */
class ConfigCapsuleProperties {

    /**
     * @var array
     */
    private $settings;

    /**
     * @var array
     */
    private $dependencies;

    public function __construct(array $settings, array $dependencies = []) {
        $this->settings     = $settings;
        $this->dependencies = $dependencies;
    }

    /**
     * @return array
     */
    public function getSettingsProperties() {
        return $this->settings;
    }

    /**
     * @return array
     * @throws DependencyInjectionException
     */
    public function getDependencyProperties()
    {

        return array_filter($this->dependencies, function ($dependency) {

            if (!($dependency instanceof DependencyInjection)) {
                throw new DependencyInjectionException("Can't create a dependency injection with incorrect format");
            }

            return $dependency;

        });
    }

}