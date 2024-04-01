<?php

namespace FBAConsulting\Libs\Slim\Strategies\Decorators;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Request;
use Slim\Interfaces\Http\HeadersInterface;

class RequestDecorator extends Request {

    public function __construct($method, UriInterface $uri, HeadersInterface $headers, array $cookies, array $serverParams, StreamInterface $body, array $uploadedFiles = [])
    {
        parent::__construct($method, $uri, $headers, $cookies, $serverParams, $body, $uploadedFiles);
    }

    public function filter() {
        print_r('filter');
    }

}