<?php

class MyDB extends mysqli{
    private static $instance = null;
    
    private $user = 'root';
    private $pass = '';
    private $dbName = 'sugarpro761';
    private $dbHost = 'localhost';
 
    public static function getInstance() {
        if(!self::$instance instanceof self){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    public function __clone() {
        trigger_error('Clone is not allowed '.E_USER_ERROR);
    }
    
    public function __wakeup() {
        trigger_error('Deserializing is not allowed '.E_USER_ERROR);
    }
    
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if(mysqli_connect_error()){
            exit('Connection Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }
    
    public function authenticateUser($username, $password) {
        
        $username = $this->real_escape_string($username);
        $password = $this->real_escape_string($password);
        
        $password_md5 = md5($password);
        
        $user_hash = self::getUserHash($username);
        
        if(empty($user_hash)) return false;
        
	if($user_hash[0] != '$' && strlen($user_hash) == 32) {
            // Old way - just md5 password
            return strtolower($password_md5) === $user_hash;
        }
        
        return crypt(strtolower($password_md5), $user_hash) === $user_hash;
        
    }
    
    function getUserHash($username){
        $queryStr = "select user_hash from users where user_name = '$username'";
        $user = $this->query($queryStr);
        writelog($queryStr);
        if($user->num_rows > 0 ){
            $row = $user->fetch_row();
            return $row[0];
        }else{
            return null;
        }
    }
    
}
