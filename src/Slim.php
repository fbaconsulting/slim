<?php

namespace FBAConsulting\Libs\Slim;

use DateTime;
use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;
use FBAConsulting\Libs\Slim\Exceptions\IsAlreadyRunningException;
use FBAConsulting\Libs\Slim\Exceptions\Routing\RouteWithoutNameException;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigCapsuleProperties;
use FBAConsulting\Libs\Slim\Strategies\Decorators\ContainerDecorator;
use FBAConsulting\Libs\Slim\Strategies\Decorators\RoutableDecorator;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Router;

class Slim extends App {

    /**
     * @var bool
     */
    private $isRunning = false;

    /**
     * @var DateTime
     */
    private $isRunningFrom;

    /**
     * Slim Container decorated isn't inherited because is defined as private property
     * @var ContainerDecorator
     */
    protected $container;

    /**
     * Direct access to the Slim router instance
     * @var Router
     */
    protected $router;

    /**
     * @var array
     */
    protected $settings;

    /**
     * Original Slim doesn't need any config to work
     * @param ConfigCapsuleProperties|null $configCapsuleProperties
     * @throws DependencyInjectionException
     */
    public function __construct(ConfigCapsuleProperties $configCapsuleProperties = null)
    {

        // Create by real instance of Container (on Slim is private) to decorate it
        // ContainerDecorator considered settings as a different property of dependencies
        $this->container = !is_null($configCapsuleProperties) ? new ContainerDecorator($configCapsuleProperties)
            : new ContainerDecorator(
                // Create a container without default settings
                new ConfigCapsuleProperties([])
            )
        ;

        // Add properties to the Slim Base vía ContainerDecorator (Container)
        parent::__construct($this->container);

        // Make accessible some crucial properties (Law of Demeter (LoD))
        $this->router    = $this->container->get('router');
        $this->settings  = $this->container->get('settings');

        // Redirect/rewrite all URLs that end in a / to the non-trailing / equivalent
        if ($this->settings['rewriteRouteTrailing']) {
            $this->rewriteRouteTrailing();
        }

    }

    /**
     * Helper to make access to the router
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * Helper to make accessible settings
     * @return array
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * Helper to make access to the base path setter used in pathFor()
     * @param $basePath
     * @return Router
     * @throws IsAlreadyRunningException
     */
    public function setBasePath($basePath) {

        // Prevent set the basePath if application is already running
        if ($this->isRunning) {
            $this->throwIfAppIsRunning("Can't add route callable after app is running");
        }

        return $this->router->setBasePath($basePath);
    }

    /**
     * @param $routePathFor
     * @param array $data
     * @param array $queryParams
     * @return string
     * @throws IsAlreadyRunningException
     */
    public function pathFor($routePathFor, array $data = [], array $queryParams = []) {

        // If app is not running, is no prepared router with routes to get pathFor
        if (!$this->isRunning) {
            $this->throwIfAppIsNotRunning(
                "Can't get a pathFor if app isn't running yet"
            );
        }

        return $this->router->pathFor($routePathFor, $data, $queryParams);
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     * @throws RouteWithoutNameException
     */
    public function get($pattern, $callable, $defaultArguments = [])
    {
        return $this->addRoute(
            new RoutableDecorator(
                $pattern, $callable, 'get'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     * @throws RouteWithoutNameException
     */
    public function post($pattern, $callable, $defaultArguments = [])
    {
        return $this->addRoute(
            new RoutableDecorator(
                $pattern, $callable, 'post'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     * @throws RouteWithoutNameException
     */
    public function put($pattern, $callable, $defaultArguments = [])
    {
        return $this->addRoute(
            new RoutableDecorator(
                $pattern, $callable, 'put'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     * @throws RouteWithoutNameException
     */
    public function delete($pattern, $callable, $defaultArguments = [])
    {
        return $this->addRoute(
            new RoutableDecorator(
                $pattern, $callable, 'delete'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     * @throws RouteWithoutNameException
     */
    private function addRoute(RoutableDecorator $routeCallable) {

        // Prevent add new routes after application is initiated
        if ($this->isRunning) {
            $this->throwIfAppIsRunning(
                "Can't add route callable after app is running because is not detected by router system"
            );
        }

        // Extract method to create the route by Slim logic
        $method = $routeCallable->getRouteMethod();

        // Set the container decorator as default container instance
        $routeCallable->setContainer($this->container);

        // Create and return the RouteInterface as Slim logic inherited
        return parent::$method(
            $routeCallable->getPattern(), $routeCallable->getCallable()
        );

    }

    /**
     * Run the application
     * @param $silent
     * @return ResponseInterface
     */
    public function run($silent = false)
    {

        // Mark as app is running to prevent add more config after init
        $this->isRunning = true;

        // And init the time of application is running
        $this->isRunningFrom = new DateTime();

        return parent::run($silent);
    }

    /**
     * Slim treats a URL pattern with a trailing slash as different to one without. That is, /user and /user/ are different and so can have different callbacks attached.
     * @link https://www.slimframework.com/docs/v3/cookbook/route-patterns.html
     */
    protected function rewriteRouteTrailing() {

        $this->add(
            function ($request, $response, callable $next) {

                $uri  = $request->getUri();
                $path = $uri->getPath();
                if ($path != '/' && substr($path, -1) == '/') {
                    // recursively remove slashes when it's more than 1 slash
                    while(substr($path, -1) == '/') {
                        $path = substr($path, 0, -1);
                    }

                    // permanently redirect paths with a trailing slash
                    // to their non-trailing counterpart
                    $uri = $uri->withPath($path);

                    if($request->getMethod() == 'GET') {
                        return $response
                            ->withRedirect((string)$uri, 301);
                    }

                    return $next($request->withUri($uri), $response);

                }

                return $next($request, $response);

            }
        );

    }

    /**
     * @throws IsAlreadyRunningException
     */
    protected function throwIfAppIsRunning($message) {
        throw new IsAlreadyRunningException(
            sprintf(
                "Exception %s. Running from %s", $message, $this->isRunningFrom->format('Y-m-d H:i:s')
            )
        );
    }

    /**
     * @throws IsAlreadyRunningException
     */
    protected function throwIfAppIsNotRunning($message) {
        throw new IsAlreadyRunningException(
            sprintf(
                "Exception: %s. Running from %s", $message, $this->isRunningFrom->format('Y-m-d H:i:s')
            )
        );
    }

}