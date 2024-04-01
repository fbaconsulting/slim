<?php

namespace FBAConsulting\Libs\Slim\Strategies\Config;

class DependencyInjection {

    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $injection;

    public function __construct($name, callable $injection) {
        $this->name      = $name;
        $this->injection = $injection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getInjection()
    {
        return $this->injection;
    }

}