<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
 
function __autoload($className)
{

    // change this
    $BASE = str_replace("/", DIRECTORY_SEPARATOR, "C:/php/src/");
    
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fileName = "vendors\\" . $fileName;

    require($BASE . $fileName);
}

require_once("routes.php");


