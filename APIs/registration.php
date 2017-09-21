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
//get all reqirerd name value
//get optional name value

if(!empty($_GET['first_name']) && !empty($_GET['last_name'])  && $_GET['first_name'] != "undefined" && !empty($_GET['email']) && $_GET['email'] != "undefined" && !empty($_GET['password']) && $_GET['password'] != "undefined" && !empty($_GET['phone']) && $_GET['phone'] != "undefined"){
include("system.php");
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$phone = $_GET['phone'];
$email = $_GET['email'];
$password = $_GET['password'];
$password1 = $password;
$job = $_GET['job'];
$city = $_GET['city'];
$state = $_GET['state'];
$site_root = "http://127.0.0.1/course/APIs/";

// set activation paramenters

$rand = rand(0,9999);
$now = time();
$activation_code = crypt($now,$rand);
$activation_code1 = $activation_code;
$tomorrow = time() + (24 * 60 * 60);
$expire_day = date('Y-m-d',$tomorrow);

//secure and validate the data colected
    
$activation_date = $expire_day;
$first_name = mysqli_real_escape_string($dbc,$first_name);
$last_name = mysqli_real_escape_string($dbc,$last_name);
$email = mysqli_real_escape_string($dbc,$email);
$password = mysqli_real_escape_string($dbc,$password);
$city = mysqli_real_escape_string($dbc,$city);
$state = mysqli_real_escape_string($dbc,$state);
$job = mysqli_real_escape_string($dbc,$job);
$phone = mysqli_real_escape_string($dbc,$phone);

//check if user exists

$query = "SELECT * FROM `users` WHERE `email`='$email' or `phone`='$phone'";
$result = mysqli_query($dbc,$query) or die("Error checking if user exists1");

$num = mysqli_num_rows($result);

if($num==0){
//add user if not exist

$query_add = "INSERT INTO `users` SET
`first_name`='$first_name',
`last_name`='$last_name',
`email`='$email',
`password`='$password',
`state`='$state',
`city`='$city',
`phone`='$phone',
`job`='$job',
`activation_code`='$activation_code',
`expiry`='$activation_date'

";
$result_add = mysqli_query($dbc,$query_add)or die('Error adding new user');
if($result_add){
//user successfully added

//mail user

$subject = "CIPM STUDY Account Registration";
$message = '
We are pleased to inform you that you have successfully registered for a CIPM STUDY User Account,
<br/>
<br/>
<a href="'.$site_root.'activation.php?activate='.$activation_code1.'&user='.$email.'">Click here to activate your account</a><br/>
or Copy this link into your browser address press enter <br/>
<a href="'.$site_root.'activation.php?activate='.$activation_code1.'&user='.$email.'">'.$site_root.'activate.php?activate='.$activation_code1.'&user='.$email.'</a>
';
@$send_mail = mail_user($email,$name,$subject,$message);

if($send_mail==1){
$error_details = 'Mail sent!';

}else{
$error_details = 'Mail NOT sent!';

}

$data = array('status'=>1,'details'=>"User added, $error_details",'activation_code'=>"$activation_code");


}else{
//failure user not added
$data = array('status'=>0,'details'=>"Request Failed retry");
}

}else{
//user exist dont add
$data = array('status'=>0,'details'=>"user exists");
}

$response = json_encode($data);

echo $response ;
}else{

$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

//echo out json
echo $response ;

}

?>