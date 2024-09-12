<?php

namespace FBAConsulting\Libs\Slim\Framework\Config;

use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;

/**
 * DTO to get involved settings and dependency injection properties as independent values
 */
class ConfigPropertiesCapsule {

    /**
     * ConfigSettingsCapsule on Slim Framework are defined an array
     *
     * @var array
     */
    private array $settings;

    /**
     * @var array
     */
    private array $dependencies;

    /**
     * AppFactory requires add settings and dependencies on the same array
     *
     * @param array $settings ConfigSettingsCapsule properties are a specification marked as index with name "settings"
     * @param array $dependencies Dependencies are added with AppFactory dependencies
     */
    public function __construct(array $settings = [], array $dependencies = []) {
        $this->settings     = $settings;
        $this->dependencies = $dependencies;
    }

    /**
     * Return the properties that will be used as settings
     * @return array
     */
    public function getSettingsProperties(): array
    {
        return $this->settings;
    }

    /**
     * Return as dependency property and check the structure if is correct
     *
     * @return array
     * @throws DependencyInjectionException thrown if dependency hasn't the correct format
     */
    public function getDependencyProperties(): array
    {

        return array_filter($this->dependencies, function ($dependency) {

            // Evaluates if is a dependency property defined correctly
            if (!($dependency instanceof AvailableLibrary)) {
                throw new DependencyInjectionException(
                    sprintf(
                        "Dependency property must be a %s class", AvailableLibrary::class
                    )
                );
            }

            return $dependency;

        });
    }

}