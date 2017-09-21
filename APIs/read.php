<?php
	if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
 
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
 
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
        exit(0);
    }

if(isset($_GET['email'])&& isset($_GET['password'])   && isset($_GET['path'])){
include_once("system.php");
    
    $path = $_GET['path'];
    $cont = file_get_contents($path);
   // print_r($content); 

$data = array('status'=>1,'cont'=>"$cont");
    $res =  json_encode($data);
    echo $res;
  
}else{
$data = array('status'=>0,'details'=>"Error, user must be logged in");
$response = json_encode($data);
echo $response ;
    }
//get the users paid courses
//get from paid where email is session email, 
//render the courses on the app
//for each course , render the materail, Check if session is active first  befre rendering 
?>

