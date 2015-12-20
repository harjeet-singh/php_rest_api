<?php
require_once 'includes/MyDB.php';
class User  {
    var $db;
    function __construct(){
        $this->db = $GLOBALS['db'];
    }
    
    function get_user_list(){
        $queryStr = "select * from users where deleted = '0'";
        $result = $this->db->query($queryStr);
        //writelog($queryStr);
        $userList = array();
        if($result->num_rows > 0 ){
            while($row = $result->fetch_assoc()){
                $userList[] =  $row;
            }
            return $userList;
        }else{
            return null;
        }
    }
}