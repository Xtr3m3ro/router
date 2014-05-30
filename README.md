# clagraff/router
A lightweight and simple PHP router, for use in developing web applications.

This router class can be used to quickly setup routing system, to map URI locations to class methods, functions or closures. You can specify the request method(s) to accept, a regular expression pattern which must be matched, and then the callable entity to handle the request.

## Usage
Simply include the Router.php file in your code, or you can drop it into your `vendor/` folder to use autoloading to grab the file. No other setup is necessary, as long as you are running `PHP 5.3` or greater.


```php
<?php

class Controller()
{
  public static function methodOne($req) {
    $req->body = "Using the \Controller::methodOne() method";
    return $req->body;
  }
  
  public function methodTwo($req) {
    $req->body = "Using the $class->methodTwo() method";
    return $req->body;
  }
}

function my_function(&$req) {
  $req->body = "Using the function 'my_function()'";
}

require_once("../vendor/__autoload.php");
$router = new \clagraff\Router("http", "/", true);

$router->add("get", "^my_function$", "my_function");
$router->add("get", "^methodOne$", "\Controller::methodOne");
$router->add("get", "^closure$", function($request) {
    $request->body = "Using a closure";
    return $request;
});

$class = Controller();
$router->add("get", "^methodTwo$", [$class, "methodTwo"]);

$router->route();
```
