<?php

namespace FBAConsulting\Libs\Slim\Strategies\Decorators;

use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigCapsuleProperties;
use FBAConsulting\Libs\Slim\Strategies\ConfigProperties;
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
 *  `request`           an instance of \Psr\Http\Message\ServerRequestInterface with our extended Request
 *  `response`          an instance of \Psr\Http\Message\ResponseInterface with our extended Response
 *  `router`            an instance of \Slim\Interfaces\RouterInterface with our RouterDecorator
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
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => true,
        'routerCacheFile'                   => false,
        // Custom extensión properties
        'rewriteRouteTrailing'              => true,
        // todo
        'namedRoutesByDefault'              => true
    ];

    /**
     * @var ConfigCapsuleProperties
     */
    private $containerInstanceProperties;

    /**
     * @throws DependencyInjectionException
     */
    public function __construct(ConfigCapsuleProperties $configCapsuleProperties)
    {

        // Set default settings with defined by user
        $this->containerInstanceProperties = array(
            // Setup settings in a specific index how is required by Slim
            'settings' => array_merge(
                $this->defaultSettings, $configCapsuleProperties->getSettingsProperties()
            )
        );

        // And work the real slim container with full properties
        parent::__construct(
            array_merge(
                $this->containerInstanceProperties, $configCapsuleProperties->getDependencyProperties()
            )
        );

        /*
'errorHandler' => function ($container) {
    return new SlimAppError($container);
},
'phpErrorHandler' => function($container) {
    return new SlimPhpError($container);
},
'notAllowedHandler' => function ($container) {
    return new SlimNotAllowedMethodError($container);
},
'notFoundHandler' => function ($container) {
    return new SlimNotFoundError($container);
}
*/

    }

}