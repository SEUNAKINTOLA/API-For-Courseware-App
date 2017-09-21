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

//get all form inputs
if(isset($_GET['email'])){
include_once("system.php");
$email =  $_GET['email'];
    
//check if user has an active session, then delete session details
$checkses = "SELECT * FROM `session` WHERE `owner`='$email'";
$checksession = mysqli_query($dbc,$checkses);
$num = mysqli_num_rows($checksession);
if($num ==1){
// from here, later work on deleting session details
$query_del = "DELETE FROM `session` WHERE
`owner`='$email'";
$result_add = mysqli_query($dbc,$query_del)or die('Error loggin in');
$data = array('status'=>1,'details'=>"logged out");
$response = json_encode($data);
echo $response;
}



    
}else{
$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

echo $response;
}
?>