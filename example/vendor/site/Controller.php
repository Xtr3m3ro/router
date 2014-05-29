<?php

namespace site;

class Controller {


    public static function error($request) {
        $request->body = "Dividing by zero!";
        $tmp = 5 / 0;
        return $request;
    }
    
    public static function fatalError($request) {
        $request->body = "Calling undefined function!";
        $tmp = call_to_undefined_function();
        return $request;
    }
    
    public static function index($request) {
        $request->body = file_get_contents(__DIR__ . "/assets/index.html");
        return $request;
    }
    
    public static function user($request, $id = -1) {
        $html = "<h1>Users</h1><h2>Viewing applicable user</h2>";

        $users = ["Curtis", "James", "Lucas", "Johnathan"];
        if ($id >= 0 && $id < count($users)) {
            $html .= "User: " . $users[$id];
        } else {
            $html .= "User not found!";
        }

        $request->body = $html;
        return $request;
    }
    
    
    public static function missing($request) {
        $body = "<h1>404 Error</h1><h2>Page not found!</h2>";
        $request->body = $body;
        
        return $request;
    }
}
