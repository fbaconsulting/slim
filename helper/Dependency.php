<?php

namespace FBAConsulting\Libs\Slim;

use InvalidArgumentException;

class Dependency
{
    private array $settings = [];
    private array $dependencies = [];
    private array $requiredDependencies = [
        'settings',
        'environment',
        'request',
        'response',
        'router',
        'foundHandler',
        'errorHandler',
        'notFoundHandler',
        'notAllowedHandler',
        'callableResolver',
    ];

    private array $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
        'removeTrailingSlash' => true,
    ];

    public function __construct(array $settings = [], array $dependencies = [])
    {
        $this->setSettings($settings);
        $this->setDependencies($dependencies);
    }

    public function setSettings(array $settings): void
    {
        $this->settings = array_merge(
            $this->defaultSettings, $settings
        );
    }

    public function setDependencies(array $dependencies): void
    {
        foreach ($dependencies as $key => $value) {
            if (!in_array($key, $this->requiredDependencies)) {
                throw new InvalidArgumentException("Invalid dependency: $key");
            }
        }
        $this->dependencies = $dependencies;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function getRequiredDependencies(): array
    {
        return $this->requiredDependencies;
    }

    public function getDefaultDependency(string $dependencyName)
    {
        switch ($dependencyName) {
            case 'environment':
                return function ($c) {
                    return new \Slim\Http\Environment($_SERVER);
                };
            case 'request':
                return function ($c) {
                    return \Slim\Http\Request::createFromEnvironment($c->get('environment'));
                };
            case 'response':
                return function ($c) {
                    $headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
                    $response = new \Slim\Http\Response(200, $headers);
                    return $response->withProtocolVersion($c['settings']['httpVersion']);
                };
            default:
                throw new InvalidArgumentException("No default implementation for {$dependencyName}");
        }
    }
}