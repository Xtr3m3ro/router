<?php
/*
    You can use this file when running PHP's internal server.
    The command would look something like:
        >> php -S localhost:80 index.php
*/


$uri = $_SERVER["REQUEST_URI"];
$possible_file = realpath(dirname(__FILE__) . $uri);

if (is_file($possible_file) && $possible_file != __FILE__) {
    return false;
} else {

    // Change this path to point to your "routes.php" file.
    require_once("../vendor/site/routes.php");
}
