<?php

namespace FBAConsulting\Libs\Slim\Framework;

use DateTime;
use FBAConsulting\Libs\Slim\Framework\Exceptions\IsAlreadyRunningException;
use FBAConsulting\Libs\Slim\Framework\Exceptions\IsNotRunningException;
use FBAConsulting\Libs\Slim\Exceptions\Container\DependencyInjectionException;
use FBAConsulting\Libs\Slim\Exceptions\Routing\RouteWithoutNameException;
use FBAConsulting\Libs\Slim\Framework\Decorators\ContainerDecorator;
use FBAConsulting\Libs\Slim\Strategies\Config\ConfigProperties;
use FBAConsulting\Libs\Slim\Strategies\Decorators\Routable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Collection;
use Slim\Interfaces\RouteInterface;
use Slim\Router;
use Throwable;

class Framework extends App {

    /**
     * @var bool
     */
    private bool $isRunning = false;

    /**
     * AppFactory Container decorated isn't inherited because is defined as private property
     * @var ContainerDecorator
     */
    protected ContainerDecorator $_container;

    /**
     * Direct access to the AppFactory router instance
     * @var Router
     */
    protected Router $_router;

    /**
     * todo Debería ser Settings object?
     * @var Collection
     */
    protected Collection $_settings;

    /**
     * Original Slim Framework doesn't need any config to work
     *
     * @param ConfigProperties $configProperties
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ConfigProperties $configProperties)
    {
        
        // Create by real instance of Container (on AppFactory is private) to decorate it
        // ContainerDecorator make settings as a different property that dependencies
        $this->_container = new ContainerDecorator($configProperties);
        
        // Add properties to the AppFactory Base vía ContainerDecorator (Container)
        parent::__construct($this->_container);

        // Make accessible some crucial properties (Law of Demeter (LoD))
        $this->_router    = $this->_container->get('router');
        $this->_settings  = $this->_container->get('settings');
        
        // Redirect/rewrite all URLs that end in a / to the non-trailing / equivalent
        if ($this->_settings['rewriteRouteTrailing']) {
            $this->setupTrailingSlashHandling();
        }

    }

    /**
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->_router;
    }

    public function getSettings()
    {
        return $this->_settings;
    }

    /**
     * Helper to make access to the base path setter used in pathFor()
     * @param string $basePath
     * @return Router
     * @throws IsAlreadyRunningException
     */
    public function setBasePath(string $basePath): Router
    {

        $this->throwIfAppIsRunning(
            sprintf(
                "Can't change the base path to %s if application is running.", $basePath
            )
        );

        return $this->getRouter()->setBasePath($basePath);
    }

    /**
     * Syntactic sugar to make accessible get method with Routable
     * @throws IsAlreadyRunningException
     */
    public function get($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'get'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible post method with Routable
     * @throws IsAlreadyRunningException
     */
    public function post($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'post'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible put method with Routable
     * @throws IsAlreadyRunningException
     */
    public function put($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'put'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible delete method with Routable
     * @throws IsAlreadyRunningException
     */
    public function delete($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'delete'
            )
        );
    }

    /**
     * @throws IsAlreadyRunningException
     */
    public function patch($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'patch'
            )
        );
    }

    /**
     * @throws IsAlreadyRunningException
     */
    public function options($pattern, $callable, $defaultArguments = []): RouteInterface
    {
        return $this->addRoute(
            new Routable(
                $pattern, $callable, 'options'
            )
        );
    }

    /**
     * Syntactic sugar to make accessible get method
     * @throws IsAlreadyRunningException
     */
    private function addRoute(Routable $routeCallable): RouteInterface
    {

        // Prevent add new routes after application is initiated
        if ($this->isRunning) {
            $this->throwIfAppIsRunning(
                "Can't add route callable after app is running because is not detected by router system"
            );
        }

        // Set the container decorator as default container instance
        $routeCallable->setContainer($this->_container);

        // Extract method to create the route by AppFactory logic
        $method = $routeCallable->getRouteMethod();

        // Create and return the RouteInterface as AppFactory logic inherited
        // return parent::$method($routeCallable->getPattern(), $routeCallable->getCallable());
        return $this->map(
            [$method], $routeCallable->getPattern(), $routeCallable->getCallable()
        );

    }

    /**
     * Run the application
     *
     * @return ResponseInterface
     * @throws IsAlreadyRunningException
     * @throws Throwable
     */
    public function listen(): ResponseInterface
    {

        $this->throwIfAppIsRunning(
            sprintf(
                "Method %s can't be loaded if app is running yet", __METHOD__
            )
        );

        // Mark as app is running to prevent add more config after init
        $this->isRunning = true;

        return parent::run(
            // If you change it to true, the framework never retrieve a http response
            $silenceResponseToTheClient = false
        );

    }

    /**
     * AppFactory treats a URL pattern with a trailing slash as different to one without. That is, /user and /user/ are different and so can have different callbacks attached.
     * @link https://www.slimframework.com/docs/v3/cookbook/route-patterns.html
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function setupTrailingSlashHandling(): void
    {
        if ($this->_container->get('settings')['removeTrailingSlash']) {
            $this->add(function ($request, $response, $next) {
                $uri = $request->getUri();
                $path = $uri->getPath();
                if ($path != '/' && substr($path, -1) == '/') {
                    $uri = $uri->withPath(substr($path, 0, -1));
                    return $response->withRedirect((string)$uri, 301);
                }
                return $next($request, $response);
            });
        }
    }

    /**
     * @throws IsAlreadyRunningException
     */
    protected function throwIfAppIsRunning(string $message) {
        if ($this->isRunning) {
            throw new IsAlreadyRunningException(
                sprintf(
                    "Exception %s. Running from %s", $message, $this->isRunningFrom->format('Y-m-d H:i:s')
                )
            );
        }
    }

    /**
     * @throws IsNotRunningException
     */
    protected function throwIfAppIsNotRunning(string $message) {
        throw new IsNotRunningException(
            sprintf(
                "Exception: %s. Running from %s", $message, $this->isRunningFrom->format('Y-m-d H:i:s')
            )
        );
    }

}