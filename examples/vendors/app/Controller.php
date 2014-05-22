<?php

namespace app;

class Controller {


    public static function index($request) {
		$Rbac = new \clagraff\Rbac();
        $request->body = "index";
        return $request;
    }
    
    
    public static function error($request) {
        $request->body = "Dividing by zero!";
        $tmp = 5 / 0;
        return $request;
    }

    
    public static function user($request, $id = -1) {
        $users = ["Curtis", "James", "Lucas", "Johnathan"];
        if ($id >= 0 && $id < count($users)) {
            $request->body = "User: " . $users[$id];
        } else {
            $request->body = "User not found!";
        }
        
        return $request;
    }
    
    
    public static function missing($request) {
        $body = "<h3>Page not found!</h3>";
        $request->body = $body;
        
        return $request;
    }
}
