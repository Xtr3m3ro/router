<?php

namespace clagraff;


class Rbac {
    public $rolesFile;
    public $dbFile;
    
    private $conn;
    
    public function __construct(
        $roles = null,
        $db=null
    ) {
        
        $base = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        
        if (is_null($roles)) {
            $roles = $base . "roles.json";
        }
        
        if (is_null($db)) {
            $db = $base . "rbac.sqlite3";
        }
        
        $this->rolesFile = $roles;
        $this->dbFile = $db;
        
        $this->readFile();
        $this->connect();
    }
    
    private function connect() {
        $this->conn = new \PDO("sqlite:" . $this->dbFile);
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='meta';";
        $result = $this->conn->query($sql)->fetch();
        
        if ($result !== FALSE) {
            $sql = "SELECT md5 FROM meta LIMIT 0,1;";
            $md5 = $this->conn->query($sql)->fetch();
            print_r($result);
        } else {
            $sql = "CREATE TABLE meta (md5 TEXT NOT NULL);";
            $this->conn->query($sql);
        }
    }
    
    private function readFile() {
        if (file_exists($this->rolesFile) == FALSE) {
            file_put_contents($this->rolesFile, "{\n    \n}");
        }
        $rolesArr = json_decode(file_get_contents($this->rolesFile), TRUE);
        $roles = new \stdClass;
        
        function process($arr, $obj) {
            foreach ($arr as $key => $value) {
                foreach ($value as $name => $contents) {
                    if (is_array($contents) || is_string($contents)) {
                        if ($name == "can") {
                            $obj->{$key} = new Role($contents);
                        }
                    }
                }
            }
            
            return $obj;
        }
        
        process($rolesArr, $roles);
        echo "<pre>";
        print_r($roles);
        echo "</pre>";
    }
    
}


class Role {
    private $perms = [];
    private $parent;
    
    public function __construct($perms) {
        if (is_array($perms) == TRUE) {
            $this->perms = $perms;
        } else if (is_string($perms) == TRUE) {
            $this->perms[] = $perms;
        } else {
            throw new \Exception("Invalid permissions object parameter");
        }
    }
}
