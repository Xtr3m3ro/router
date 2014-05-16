<?php

namespace clagraff;

class Router {
    public $base_path;
    public $catch_all = true;
    public $delimiter = "#";
    public $routes = Array();
    public $schema;
    public $status_handlers = Array();
    
    private $methods = Array("post", "get", "post", "delete");
    private $request;
    
    
    public function __construct($schema = "http", $path = "/") {
        $this->schema = $schema . "://";
        $this->base_path = $path;
        $this->set_status_handle(200, [$this, "fetch_request_body"]);
        $this->set_status_handle(500, [$this, "fetch_request_error"]);
        $this->set_status_handle(404, [$this, "fetch_request_missing"]);
    }
    
    
    public function add_route($method, $regex, $handler) {
        $route = new \stdClass;
        
        $route->method = $method;
        $route->regex = $regex;
        $route->handler = $handler;
        
        $this->routes[] = $route;
    }
    
    
    public function set_status_handle($status, $handler) {
        $this->status_handlers[$status] = $handler;
    }
    
    
    private function create_request() {
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
    
    
    private function fetch_request_body($request) {
        return $request;
    }
    
    
    private function fetch_request_error($request) {
        $request->body = "<pre><code>";
        $request->body .= print_r(debug_backtrace(), true);
        $request->body .= print_r($request->meta->error, true);
        $request->body .+ "</code></pre>";
        
        return $request;
    }
    
    private function fetch_request_missing($request) {
        $request->body = "<h2>404</h2><h3>Page not found!</h3>";
        return $request;
    }
    
    
    private function find_route($request) {
        $modified_uri = str_replace($this->base_path, "", $request->uri);
        
        foreach ($this->routes as $route) {
            if (strtolower($route->method) != strtolower($request->method)) {
                continue;
            }
            
            if (strlen($modified_uri) == 0 && strlen($route->regex) == 0) {
                return $route;
            } else if (
                preg_match($this->delimiter . $route->regex . $this->delimiter, $modified_uri) &&
                    strlen($route->regex) > 0) {
                return $route;
            }
        }
        return null;
    }
    
    private function get_url_groups($request, $route) {
        $modified_uri = str_replace($this->base_path, "", $request->uri);

        preg_match($this->delimiter . $route->regex . $this->delimiter, $modified_uri, $matches);
        
        $groups = [];
        
        if (count($matches) >= 2) {
            $groups =  array_slice($matches, 1, count($matches)-1, true);
        }  

        return $groups;
    }
    
    public function route() {
        $request = $this->create_request();
        $request->method = $_SERVER["REQUEST_METHOD"];
        $request->url = $this->schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $request->uri = $_SERVER["REQUEST_URI"];
        
        
        $route = $this->find_route($request);
        $groups = [];
        
        if ($route == null) {
            $request->status = 404;
        } else {
            $request->status = 200;
            $groups = $this->get_url_groups($request, $route);
            
  
            $this->setup();
            try {
                $request = call_user_func_array($route->handler, [$request] + $groups);
            } catch (\Exception $error) {
                $request->meta->error = $error;
                $request->status = 500;
            }
            $this->tear_down();
            
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
    }
    
    private function tear_down() {
        if ($this->catch_all) {
            restore_error_handler();
        }
    }
}

phpinfo();