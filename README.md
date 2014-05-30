# clagraff/router
A lightweight and simple PHP router, for use in developing web applications.

This router class can be used to quickly setup routing system, to map URI locations to class methods, functions or closures. You can specify the request method(s) to accept, a regular expression pattern which must be matched, and then the callable entity to handle the request.

## Installation
Simply include the Router.php file in your code, or you can drop it into your `vendor/` folder to use autoloading to grab the file. No other setup is necessary, as long as you are running `PHP 5.3` or greater.

## Usage
### Basic routing
First, you must include the Router.php file, and create a new instance of the Router class.

#### Router setup
```php
<?php
require_once("../vendor/__autoload.php");
$router = new \clagraff\Router();
?>
```

When initializing the `Router` class, you can provide any of the three, optinal parameters:

1. Which scheme to use, such as `http` or `https` (defaults to: `http`)
2. The base path of the URI to expect (defaults to: `/`/)
3. A boolean to set whether the router should display debug information on caught errors (defualts to: `true`).

#### Match against a request method
Next, to add possible routes, use the `Router->add(method, pattern, callable)` method.
For example:

```php
<?php
require_once("../vendor/__autoload.php");
$router = new \clagraff\Router("http", "/", true);

$router->add("get", "^$|^index$", "\site\Controller::index");
?>
```
This will cause all attempts to access `http://example.com/` and `http://example.com/index` the **public, static method** `\site\\Controller::index`.

You can specify a single HTTP method to accept, such as `get`, `post`, `delete`, `put`, etc. You can also use an array to specify multiple methods, or use `null` to accept any method. For example:


```php
<?php
require_once("../vendor/__autoload.php");
$router = new \clagraff\Router("http", "/", true);

$router->add("get", "^all_posts$", "\site\Controller::posts"); // Only works on GET requests
$router->add(["put", "post"], "^article$", "\site\Controller::new_article"); // Only works on PUT and POST requests
$router->add(null, "^index$", "\site\Controller::index"); // Will work with any request method.

$router->route();
?>
```

#### Using a regular expression pattern
The second parameter to the `Router->add()` method is the regular expression pattern to use when comparing against the requested, modified URI.

When you create an instance of the `Router` class, you can either specify a base URI path, or the router uses a default of `/`. When routing, and when comparing against any regular expression patterns, the base URI path is removed from the requested URI.
Therefore, for a base URI of `/`, the URL `http://example.com/index` would have a modified URI of `index`. Notice the missing `/` at the beginning.

#### Providing a callable handler
Finally, the third parameter you must provide is either the name of a function; a pubilc, static class method; a closure; or an array, with a class instance and a method name as elements. For example:

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
