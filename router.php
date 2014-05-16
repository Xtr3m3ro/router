<?php
// router.php

$uri = $_SERVER["REQUEST_URI"];
$possible_file = realpath(dirname(__FILE__) . $uri);

if (is_file($possible_file) && $possible_file != __FILE__) {
    return false;    // serve the requested resource as-is.
} else { 
    require_once("C:\php\src\my-site\bootstrap.php");
}