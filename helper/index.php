<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use FBAConsulting\Libs\Slim\AppFactory;
use FBAConsulting\Libs\Slim\Dependency;
use Slim\Http\Request;
use Slim\Http\Response;

$dependencies = new Dependency(
    [
        'settings' => [
            'displayErrorDetails' => true,
        ]
    ]
);

AppFactory::setup($dependencies);
$app = AppFactory::instance();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->run();
