<?php

namespace FBAConsulting\Libs\Slim\Strategies\Decorators;

use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigCapsuleHandlers;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigCapsuleProperties;
use FBAConsulting\Libs\Slim\Strategies\Config\DefaultConfigCapsuleHandlers;
use Slim\Container;

/**
 * This is an extension of Slim Container used as decorator
 *
 * Slim's default DI container is Pimple.
 *
 * Slim\App expects a container that implements Psr\Container\ContainerInterface
 * with these service keys configured and ready for use:
 *
 *  `settings`          an array or instance of \ArrayAccess with Slim config properties
 *  `environment`       an instance of \Slim\Http\Environment
 *  `request`           an instance of \Psr\Http\Message\ServerRequestInterface
 *  `response`          an instance of \Psr\Http\Message\ResponseInterface
 *  `router`            an instance of \Slim\Interfaces\RouterInterface
 *  `foundHandler`      an instance of \Slim\Interfaces\InvocationStrategyInterface
 *  `errorHandler`      a callable with the signature: function($request, $response, $exception)
 *  `notFoundHandler`   a callable with the signature: function($request, $response)
 *  `notAllowedHandler` a callable with the signature: function($request, $response, $allowedHttpMethods)
 *  `callableResolver`  an instance of \Slim\Interfaces\CallableResolverInterface we don't want to modify or extend the Slim callable resolver
 */
class ContainerDecorator extends Container {

    /**
     * Can't be inherited from container because are wrote as private properties...
     * We use our default properties as default and our resolvers decorators and is not involve by default over "settings" index
     *
     * @var array
     */
    private $defaultSettings = [
        // Modify extension properties
        'displayErrorDetails'   => true,
        // Custom extensión properties
        'rewriteRouteTrailing'  => true,
        // todo
        'namedRoutesByDefault'  => true,
        'forceCreateController' => true
    ];

    /**
     * @var ConfigCapsuleProperties
     */
    private $containerInstanceProperties;

    /**
     * @param ConfigCapsuleProperties $configCapsuleProperties
     * @param ConfigCapsuleHandlers|null $configCapsuleHandlers
     * @throws DependencyInjectionException
     */
    public function __construct(ConfigCapsuleProperties $configCapsuleProperties, ConfigCapsuleHandlers $configCapsuleHandlers = null) {

        // Set default settings with defined by user
        $this->containerInstanceProperties = array(
            // Setup settings in a specific index how is required by Slim
            'settings' => array_merge(
                $this->defaultSettings, $configCapsuleProperties->getSettingsProperties()
            )
        );

        // Check if is required the default config capsule handlers
        if (is_null($configCapsuleHandlers)) {
            $configCapsuleHandlers = new DefaultConfigCapsuleHandlers($this);
        }

        // Define the error handlers
        $this->containerInstanceProperties['errorHandler']      = $configCapsuleHandlers->getErrorHandler();
        $this->containerInstanceProperties['notFoundHandler']   = $configCapsuleHandlers->getNotFoundHandler();
        $this->containerInstanceProperties['notAllowedHandler'] = $configCapsuleHandlers->getNotAllowedHandler();
        $this->containerInstanceProperties['phpErrorHandler']   = $configCapsuleHandlers->getPhpErrorHandler();

        // And work the real slim container with full properties
        parent::__construct(
            array_merge(
                $this->containerInstanceProperties, $configCapsuleProperties->getDependencyProperties()
            )
        );

        // todo Testear PHP Fatal error:  Uncaught TypeError: Argument 1 passed to FBAConsulting\Libs\Slim\Strategies\Config\Handlers\DefaultPhpErrorHandler::__invoke()
        // $this->containerInstanceProperties['request'] = RequestDecorator::class;

    }

    /**
     * Make accesible the error display if is enabled
     * @return bool
     */
    public function isEnableDisplayError() {
        return $this->containerInstanceProperties['settings']['displayErrorDetails'];
    }

}