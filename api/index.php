<?php

session_start(['cookie_lifetime' => 300]);

// using
use Router\Router;

// load config and startup files
require 'config.php';
require CORE . 'Startup.php';

// create object of request and response class
$request = new Http\Request();
$response = new Http\Response();

$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$response->setHeader('Content-Type: application/json; charset=UTF-8');

// set request url and method
$router = new Router('/' . $request->getUrl(), $request->getMethod());

// load defined routes
require 'routes.php';

// run router
$router->run();

// render response
$response->render();
