<?php

require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php';

use FBAConsulting\Libs\Slim\Slim;

$app = new Slim();

class ControllerTest {

    public function __invoke($request, $response, $args) {
        print_r('<pre>');
        print_r($request);
        print_r('</pre>');
    }

}

$app->get('/', ControllerTest::class)
    ->setName(
        'index'
    )
;

$app->run();