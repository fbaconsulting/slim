<?php

namespace FBAConsulting\Libs\Slim\Strategies\Http;

use Slim\Http\Request;
use Slim\Http\Response;

interface Middleware {

    public function __invoke(Request $request, Response $response, callable $next);

}