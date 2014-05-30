<?php

require_once("../vendor/__autoload.php");

$router = new \clagraff\Router("http", "/", true);


$router->add("get", "^$|^index", "\site\Controller::index");

$router->add(["get", "post"], "^error$", "\site\Controller::error");
$router->add(["get", "post"], "^fatalError$", "\site\Controller::fatalError");

$router->add(null, "^id=([0-9]+)$", "\site\Controller::user");

$router->add("get", "^closure$", function($request) {
    $request->body = "This is a test!";
    return $request;
});


$router->add("get", "^request", "\site\Controller::request");

$router->route();
