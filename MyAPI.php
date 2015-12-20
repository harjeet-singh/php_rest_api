<?php
session_start();
require_once 'API.php';
require_once 'includes/MyDB.php';
require 'include.php';
class MyAPI extends API
{
    public function __construct($request, $origin) {
        parent::__construct($request);
        
        //Check if token present in the header
        if (!array_key_exists('Token', $this->headers) && $request != 'get_token') {
            throw new Exception('No API Token provided');
        }
        
        //Authenticate Token
        if(!in_array($this->headers['Token'], $_SESSION) && $request != 'get_token'){
            throw new Exception('Expired or Invalid API Token');
        }
    }

    /**
     * Example of an Endpoint
     */
     protected function get_user_list() {
        if ($this->method == 'GET') {
            $User = new User();
            return $User->get_user_list();
        } else {
            throw new Exception('Only accepts GET requests');
        }
     }
     
     protected function get_token() {
        if ($this->method == 'POST') {
            $username = $this->request['username'];
            $password = $this->request['password'];
            
            if(isset($username) && isset($password)){
                
                if(isset($_SESSION[$username])){
                    return $_SESSION[$username];
                }
                else{
                    $user_authenticated = MyDB::getInstance()->authenticateUser($username, $password);
                    if($user_authenticated){
                        //$this->User->loadUser($username, $password);
                        $_SESSION[$username] = uniqid();
                        return $_SESSION[$username];
                    }
                    else{
                        throw new Exception('Invalid user credentials');
                    }
                }
            }
            else{
                throw new Exception('Missing username or password');
            }
        
        } else {
            throw new Exception('Wrong request type');
        }
     }
 }


