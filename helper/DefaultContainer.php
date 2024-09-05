<?php

namespace FBAConsulting\Libs\Slim;

use Psr\Container\ContainerInterface;
use Slim\Container;

class DefaultContainer extends Container implements ContainerInterface
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }

    // Aquí puedes agregar métodos personalizados para manejar dependencias
}