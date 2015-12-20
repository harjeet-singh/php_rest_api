<?php
require_once 'includes.php';
class User{
    var $token;
    private $username;
    private $password;
    
    function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
    
    function getToken(){
        $username = $this->username;
        $password = $this->password;
        
        
        if(isset($username) && isset($password)){
            if(isset($_SESSION[$username])){
                return $_SESSION[$username];
            }
            else{
                $user = MyDB::getInstance()->getUser($username, $password);
                if($user){
                    $_SESSION[$username] = uniqid();
                    return $_SESSION[$username];
                }
                else{
                    return 'Invalid user credentials';
                }
            }
        }
        else{
            return 'Missing username/password';
        }
        
        
    }
    
    function get($method, $token){
        if($method == 'token'){
            if($_SESSION[$this->username] == $token){
                return true;
            }
            else{
                return false;
            }
        }
    }
}
