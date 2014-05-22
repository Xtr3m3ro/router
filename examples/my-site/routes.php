<?php

$router = new \clagraff\Router();

$router->add_route("get", "", "\app\Controller::index");
$router->add_route("get", "error", "\app\Controller::error");
$router->add_route("get", "id=([0-9]+)", "\app\Controller::user");

#$router->set_status_handle(404, "\app\Controller::missing");

$router->route();
