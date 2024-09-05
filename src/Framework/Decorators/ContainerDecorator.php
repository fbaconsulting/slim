<?php

namespace FBAConsulting\Libs\Slim\Framework\Decorators;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Slim\Collection;
use Slim\Container;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigCapsuleHandlers;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigProperties;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultConfigCapsuleHandlers;
use Slim\Router;

/**
 * This is an extension of AppFactory Container used as decorator
 *
 * AppFactory's default DI container is Pimple.
 *
 * AppFactory\App expects a container that implements Psr\Container\ContainerInterface
 * with these service keys configured and ready for use:
 *
 *  `settings`          an array or instance of \ArrayAccess with AppFactory config properties
 *  `environment`       an instance of \AppFactory\Http\Environment
 *  `request`           an instance of \Psr\Http\Message\ServerRequestInterface
 *  `response`          an instance of \Psr\Http\Message\ResponseInterface
 *  `router`            an instance of \AppFactory\Interfaces\RouterInterface
 *  `foundHandler`      an instance of \AppFactory\Interfaces\InvocationStrategyInterface
 *  `errorHandler`      a callable with the signature: function($request, $response, $exception)
 *  `notFoundHandler`   a callable with the signature: function($request, $response)
 *  `notAllowedHandler` a callable with the signature: function($request, $response, $allowedHttpMethods)
 *  `callableResolver`  an instance of \AppFactory\Interfaces\CallableResolverInterface we don't want to modify or extend the AppFactory callable resolver
 */
class ContainerDecorator implements ContainerInterface
{

    /**
     * @var Container
     */
    private Container $container;

    private array $defaultSettings = [
        'displayErrorDetails' => true,
        'rewriteRouteTrailing' => true,
    ];

    private array $containerInstanceProperties;

    public function __construct(
        ConfigProperties      $configCapsuleProperties,
        ConfigCapsuleHandlers $configCapsuleHandlers = null
    ) {

        $this->containerInstanceProperties = [
            'settings' => array_merge(
                $this->defaultSettings, 
                $configCapsuleProperties->getSettingsProperties()
            )
        ];

        if (is_null($configCapsuleHandlers)) {
            $configCapsuleHandlers = new DefaultConfigCapsuleHandlers($this);
        }

        // todo move to AppFactory to make configurable
        $this->containerInstanceProperties['errorHandler'] = $configCapsuleHandlers->getErrorHandler();
        $this->containerInstanceProperties['notFoundHandler'] = $configCapsuleHandlers->getNotFoundHandler();
        $this->containerInstanceProperties['notAllowedHandler'] = $configCapsuleHandlers->getNotAllowedHandler();
        $this->containerInstanceProperties['phpErrorHandler'] = $configCapsuleHandlers->getPhpErrorHandler();

        $this->container = new Container(
            array_merge(
                $this->containerInstanceProperties, 
                $configCapsuleProperties->getDependencyProperties()
            )
        );
    }

    /**
     * Maintain compatibility with the ContainerInterface
     *
     * @param $id
     * @return mixed
     * @throws ContainerExceptionInterface
     */
    public function get($id) {
        // Retrieve out custom method
        return $this->getProperty($id);
    }

    /**
     * Method to decorate the get method checking is property is defined and retrieve a custom error
     *
     * @throws ContainerExceptionInterface
     */
    private function getProperty(string $propertyIdentifier) {

        if (!$this->has($propertyIdentifier)) {
            throw new \RuntimeException(
                sprintf(
                    "Property with identifier %s is not found", $propertyIdentifier
                )
            );
        }

        return $this->container->get($propertyIdentifier);

    }

    /**
     * Maintain compatibility with the ContainerInterface
     *
     * @param $id
     * @return bool
     */
    public function has($id) {

        // Find the property as a string
        $findPropertyIdentifier = (string) $id;

        return $this->container->has($id);
    }

    /**
     * Slim stores settings as a class collection
     *
     * @return Collection
     * @throws ContainerExceptionInterface
     */
    public function getSettings(): Collection {
        return $this->getProperty('settings');
    }

    /**
     * todo Modificar para devolver nuestro propio Router (RouterDecorator)
     * @return Router
     * @throws ContainerExceptionInterface
     */
    public function getRouter(): Router {
        return $this->getProperty('router');
    }

    /**
     * Accessible method to know is the error display is enable
     *
     * @return bool
     */
    public function isEnableDisplayError(): bool
    {
        return $this->containerInstanceProperties['settings']['displayErrorDetails'];
    }
    
}