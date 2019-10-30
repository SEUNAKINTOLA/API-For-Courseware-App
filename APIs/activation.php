<?php
	if(isset($_GET['activate']) && isset($_GET['user'])){
	include_once("system.php");
$correcto = 1;//debug
	if($correcto){
//get activation code

//get activation code

$activate1 = $_GET['activate'];
$user = $_GET['user'];
$activate = mysqli_real_escape_string($dbc,$activate1);
$user = mysqli_real_escape_string($dbc,$user);
        
//check in database if activation code has not expired

$today = date("Y-m-d");
$query = "SELECT `id` FROM `users` WHERE `email`='$user' and `activation_code`='$activate' and `expiry`>='$today'";

$result = mysqli_query($dbc,$query) or die ('error checking if user exists');
$isithtere =mysqli_num_rows($result);
if($isithtere != 0){
//reset link valid
//activate account or send error as required
    
    $query_activate = "UPDATE `users` SET 
    `activation_code`='',
    `expiry`=''
    WHERE 
    `activation_code`='$activate' and `email`='$user'
    ";
$res_update = mysqli_query($dbc,$query_activate)or die('Error Updating activation details');
	//respond with successful activation
	if($res_update){
	$data = array('status'=>1,'details'=>"Account Activated");
	
	}else{
	
	$data = array('status'=>0,'details'=>"Error activating account");
	
	}
	
	}else{
	//link has expired
	//link expired, delete user 
	$query_delete = "DELETE FROM `users` WHERE `activation_code`='$activate' and `email`='$user' LIMIT 1";
	$res_delete = mysqli_query($dbc,$query_delete)or die('Error Deleting expired users contact Nescrow and Quote this error :)');
	$data = array('status'=>0,'details'=>"Account may have been activated or Activation period has passed or user hasnt registered");
	
	
	}
	}else{
		//Tampered data
		$data = array('status'=>0,'details'=>"tampered data");
	}
	$response = json_encode($data);
echo $response ;
}else{
$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

echo $response ;

}
?>