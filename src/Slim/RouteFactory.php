<?php

namespace FBAConsulting\Libs\Slim;

use FBAConsulting\Libs\Slim\Framework\Exceptions\IsNotRunningException;
use Slim\Interfaces\RouteInterface;

class RouteFactory {

    /**
     * Add GET route
     *
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function get(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['GET'], $pattern, $callable);
    }

    /**
     * Add POST route
     *
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function post(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['POST'], $pattern, $callable);
    }

    /**
     * Add PUT route
     *
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function put(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['PUT'], $pattern, $callable);
    }

    /**
     * Add PATCH route
     *
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function patch(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['PATCH'], $pattern, $callable);
    }

    /**
     * Add DELETE route
     *
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function delete(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['DELETE'], $pattern, $callable);
    }

    public static function options($pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map(['OPTIONS'], $pattern, $callable);
    }

    public static function any(string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()
            ->map(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $pattern, $callable);
    }

    /**
     * Add route with multiple methods
     *
     * @param  string[]        $methods  Numeric array of HTTP method names
     * @param string $pattern  The route URI pattern
     * @param  callable|string $callable The route callback routine
     *
     * @return RouteInterface
     */
    public static function map(array $methods, string $pattern, $callable): RouteInterface
    {
        return AppFactory::instance()->getFramework()->map($methods, $pattern, $callable);
    }

    /**
     * Add a route that sends an HTTP redirect
     *
     * @param string $from
     * @param string|UriInterface $to
     * @param int $status
     *
     * @return RouteInterface
     */
    public static function redirect(string $from, $to, int $status = 302): RouteInterface
    {
        return AppFactory::instance()->getFramework()->redirect(
            $from, $to, $status
        );
    }

    /**
     * Add a route group
     *
     * This method accepts a route pattern and a callback. All route
     * declarations in the callback will be prepended by the group(s)
     * that it is in.
     *
     * @param string $pattern
     * @param callable|Closure $callable
     *
     * @return RouteGroupInterface
     */
    public static function group(string $pattern, $callable): RouteGroupInterface
    {
        return AppFactory::instance()->group($pattern, $callable);
    }

    /**
     * Get the built path for route request with the name
     *
     * @param string $routePathFor
     * @param array $data
     * @param array $queryParams
     * @return string
     */
    public function pathFor($routePathFor, array $data = [], array $queryParams = []): string
    {

        // todo Check is the route is defined...

        // Call the router real pathFor method
        return AppFactory::instance()
            ->getRouter()
            ->pathFor($routePathFor, $data, $queryParams);
    }

}