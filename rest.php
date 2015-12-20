<?php
date_default_timezone_set('America/New_York');

//writelog($_REQUEST);
require_once 'MyAPI.php';
// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}



function writelog($message){
        $file = fopen("api.log","a");
        $date = new DateTime('NOW');
        $date = $date->format("Y M d D h:g:i a");

        if(is_array($message)){
            fwrite($file, '['.$date.'] '.print_r($message,true));
        }
        else{
            fwrite($file, '['.$date.'] '.$message);
        }
        fwrite($file, PHP_EOL);
        fclose($file);
    }
?>
