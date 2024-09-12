<?php

namespace FBAConsulting\Libs\Slim;

use DateTime;
use FBAConsulting\Libs\Slim\Framework\Config\ConfigPropertiesCapsule;
use FBAConsulting\Libs\Slim\Framework\Framework;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Router;

class AppFactory {

    /**
     * @var Framework
     */
    private Framework $framework;

    /**
     * @var AppFactory
     */
    private static AppFactory $_instance;

    /**
     * @var bool
     */
    private bool $isRunning = false;

    /**
     * @var bool
     */
    private bool $isPrepared = false;

    /**
     * @var DateTime
     */
    private DateTime $isRunningFrom;

    /**
     * Config with the framework instance made into setup method
     * @param Framework $framework
     */
    private function __construct(Framework $framework)
    {

        // Create the Framework entity to communicate with Slim Framework
        $this->framework = $framework;

        // Mark as AppFactory is already setup
        $this->isPrepared = true;

    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent serializing of the instance
    public function __wakeup()
    {
        throw new \RuntimeException("Cannot serialize the instance");
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function setup(ConfigPropertiesCapsule $configProperties) {

        // Check if setup is already enable to prevent any modification or inconsistency
        if (isset(self::$_instance)) {
            throw new \RuntimeException(
                sprintf(
                    "App can't be setup when is running yet: running from %s", self::$_instance->isRunningFrom
                )
            );
        }

        // Is app is not running and is the first setup
        self::$_instance = new self(
            new Framework($configProperties)
        );

    }

    /**
     * Call the instance. If is not prepared, the framework doesn't work
     *
     * @return AppFactory
     */
    public static function instance(): AppFactory
    {

        // Uncaught Error: Typed static property FBAConsulting\Libs\Slim\AppFactory::$_instance must not be accessed before initialization
        if (!isset(self::$_instance)) {

            throw new \RuntimeException(
                "Framework can't be run without a setup."
            );

        } else {

            // Check if application is enable, if it was correctly prepared
            if (!self::$_instance->isPrepared()) {
                throw new \RuntimeException(
                    "Can't call the framework instance if setup is not prepared"
                );
            }

        }

        return self::$_instance;

    }

    /**
     * Check if the application is already prepared
     *
     * @return bool
     */
    public function isPrepared(): bool
    {
        return $this->isPrepared;
    }

    /**
     * @return Framework
     */
    public function getFramework(): Framework
    {
        return $this->framework;
    }
    
    /**
     * 
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->framework->getRouter();
    }

    /**
     * 
     * @return array
     */
    public function getSettings(): array
    {
        return $this->framework->getSettings();
    }

    /**
     * Run the application
     * @param bool $silent
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function run(bool $silent = false): ResponseInterface
    {

        // Mark as app is running to prevent add more config after init
        $this->isRunning = true;

        // And init the time of application is running
        $this->isRunningFrom = new DateTime();

        // Run the application with listen method
        return $this->getFramework()->listen();
    }

}