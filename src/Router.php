<?php

namespace clagraff;

class Router {
    public $basePath;
    public $catch_all = true;
    public $debug = true;
    public $delimiter = "#";
    public $routes = Array();
    public $schema;
    public $status_handlers = Array();
    
    private $methods = Array("post", "get", "post", "delete");
    private $request;
    
    
    public function __construct($schema = "http", $path = "/", $debug = true) {
        $this->schema = $schema . "://";
        $this->basePath = $path;
        $this->debug = $debug;
        $this->setHandle(200, [$this, "fetchRequestBody"]);
        $this->setHandle(500, [$this, "fetchRequestError"]);
        $this->setHandle(404, [$this, "fetchRequestMissing"]);
    }
    
    
    public function add($method, $regex, $handler) {
        $route = new \stdClass;
        
        $route->method = $method;
        $route->regex = $regex;
        $route->handler = $handler;
        
        $this->routes[] = $route;
    }
    
    
    public function setHandle($status, $handler) {
        $this->status_handlers[$status] = $handler;
    }
    
    
    private function createRequest() {
        $request = new \stdClass;
        $request->body = "";
        $request->method = "";
        $request->url = "";
        $request->uri = "";
        $request->handler = "";
        $request->status = 500;
        $request->meta = new \stdClass;
        
        return $request;
    }
    
    
    private function fetchRequestBody($request) {
        return $request;
    }
    
    
    private function fetchRequestError($request) {
        ob_clean();
        $debug = $this->debug;
        include("assets/500.php");
        $request->body = ob_get_clean();
        
        return $request;
    }
    
    public function fetchRequestFatal() {
        $error = error_get_last();
        if ($error == null) {
            return;
        } else {
            $error["name"] = Array(
                E_ERROR => "E_ERROR",
                E_WARNING => "E_WARNING",
                E_PARSE => "E_PARSE",
                E_NOTICE => "E_NOTICE",
                E_CORE_ERROR => "E_CORE_ERROR",
                E_CORE_WARNING => "E_CORE_WARNING",
                E_COMPILE_ERROR => "E_COMPILE_ERROR",
                E_COMPILE_WARNING => "E_COMPILE_WARNING",
                E_USER_ERROR => "E_USER_ERROR",
                E_USER_WARNING => "E_USER_WARNING",
                E_USER_NOTICE => "E_USER_NOTICE",
                E_STRICT => "E_STRICT",
                E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
                E_DEPRECATED => "E_DEPRECATED",
                E_USER_DEPRECATED => "E_USER_DEPRECATED",
                E_ALL => "E_ALL"
            )[$error["type"]];
        }
        
        ob_clean();
        $request = $this->createRequest();
        include("assets/fatal.php");
        $request->body = ob_get_clean();
        
        print($request->body);
    }
    
    private function fetchRequestMissing($request) {
        ob_clean();
        include("assets/404.php");
        $request->body = ob_get_clean();
        return $request;
    }
    
    
    private function findRoute($request) {
        $pos = strpos($request->uri, $this->basePath);
        $modifiedUri = substr($request->uri, $pos + 1, strlen($request->uri));
        
        $requestMethod = strtolower($request->method);
        
        foreach ($this->routes as $route) {
            if (is_array($route->method) == true) {
                $routeMethod = array_map('strtolower', $route->method);
                if (in_array($requestMethod, $routeMethod) == false) {
                    continue;
                }
            } else if (is_string($route->method) == true) {
                $routeMethod = strtolower($route->method);
                if ($routeMethod != $requestMethod) {
                    continue;
                }
            } else if (is_null($route->method) == false) {
                continue;
            }
            

            
            if (strlen($modifiedUri) == 0 && strlen($route->regex) == 0) {
                return $route;
            } else if (
                preg_match(
                    $this->delimiter . $route->regex . $this->delimiter,
                    $modifiedUri
                )
                && strlen($route->regex) > 0
            ) {
                return $route;
            }
        }
        return null;
    }
    
    private function getUrlGroups($request, $route) {
        $modified_uri = str_replace($this->basePath, "", $request->uri);

        preg_match($this->delimiter . $route->regex . $this->delimiter, $modified_uri, $matches);
        
        $groups = [];
        
        if (count($matches) >= 2) {
            $groups =  array_slice($matches, 1, count($matches)-1, true);
        }  

        return $groups;
    }
    
    public function route() {
        $request = $this->createRequest();
        $request->method = $_SERVER["REQUEST_METHOD"];
        $request->url = $this->schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $request->uri = $_SERVER["REQUEST_URI"];
        
        
        $route = $this->findRoute($request);
        $groups = [];
        
        if ($route == null) {
            $request->status = 404;
        } else {
            $request->status = 200;
            $groups = $this->getUrlGroups($request, $route);
            
  
            $this->setup();
            try {
                $request = call_user_func_array($route->handler, [$request] + $groups);
                
            } catch (\Exception $error) {
                $request->meta->error = $error;
                $request->status = 500;
            }

            $this->tearDown();
            
        }
        if (array_key_exists($request->status, $this->status_handlers)) {
            $request = call_user_func_array($this->status_handlers[$request->status], [$request] + $groups);
        } else {
            throw new \Exception("Route is unmatchable");
        }
        
        print($request->body);
    }
    
    private function setup() {
        if ($this->catch_all) {
            set_error_handler(function($errno, $errstr, $errfile, $errline) {
                throw new \RuntimeException($errstr . " on line " . $errline . " in file " . $errfile);
            });  
        }
        register_shutdown_function(Array($this, "fetchRequestFatal"));
        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }
    
    private function tearDown() {
        if ($this->catch_all) {
            restore_error_handler();
        }
    }
}
