<?php
session_start();
require_once 'API.php';
require_once 'includes/MyDB.php';
class MyAPI extends API
{
    protected $User;

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
     protected function example() {
        if ($this->method == 'GET') {
            return "Your name is " . $this->User->name;
        } else {
            return "Only accepts GET requests";
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
                        return 'Invalid user credentials';
                    }
                }
            }
            else{
                return 'Missing username/password';
            }
        
        } else {
            return "Only accepts POST requests";
        }
     }
 }


