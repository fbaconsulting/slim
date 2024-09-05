<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

use FBAConsulting\Libs\Slim\AppFactory;

class AvailableLibrary {

    /**
     * @var string
     */
    private string $name;

    /**
     * @var AvailableLibrary
     */
    private $injection;

    public function __construct($name, callable $injection) {
        $this->name      = $name;
        $this->injection = new $injection(
            AppFactory::instance()->getSettings()
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getInjection(): callable
    {
        return $this->injection;
    }

}