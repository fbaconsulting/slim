<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

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

    /**
     * Slim requires add settings and dependencies on the same array
     *
     * @param array $settings Settings properties are a specification marked as index with name "settings"
     * @param array $dependencies Dependencies are added with Slim dependencies
     */
    public function __construct(array $settings, array $dependencies = []) {
        $this->settings     = $settings;
        $this->dependencies = $dependencies;
    }

    /**
     * Return the properties that will be used as settings
     * @return array
     */
    public function getSettingsProperties() {
        return $this->settings;
    }

    /**
     * Return as dependency property and check the structure if is correct
     * @return array
     * @throws DependencyInjectionException thrown if dependency hasn't the correct format
     */
    public function getDependencyProperties()
    {

        return array_filter($this->dependencies, function ($dependency) {

            // Evaluates if is a dependency property defined correctly
            if (!($dependency instanceof DependencyInjectionProperty)) {
                throw new DependencyInjectionException(
                    sprintf(
                        "Dependency property must be a %s class", DependencyInjectionProperty::class
                    )
                );
            }

            return $dependency;

        });
    }

}