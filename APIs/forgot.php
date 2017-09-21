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
            header("Access-Control-Allow-Headers:   {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
 
        exit(0);
    }

if(isset($_GET['email'])){
include_once("system.php");
    
$rand = rand(0,9999);
$now = time();
$code = crypt($now,$rand);
$code1 = $code;
$site_root = "http://127.0.0.1/course/APIs/";


//get the parameters for login email and password
$email = $_GET['email'];
$email = mysqli_real_escape_string($dbc,$email);

//check if user email and password is correct and account status is active
$table_users = 'users';
$query = "SELECT * FROM `$table_users` WHERE `email`='$email' AND`activation_code`=''";
$check_result = mysqli_query($dbc,$query)
or die('Error Querying Database');
$result_num = mysqli_num_rows($check_result);
if($result_num ==0){
//not a valid user 
$data = array('status'=>0,'details'=>"Not a valid user");
$response = json_encode($data);
echo $response ;
}else{
    
//user details
$row = mysqli_fetch_array($check_result);
$email = $row['email'];
$name = $row['first_name'].' '.$row['last_name'];
  
$query_add = "INSERT INTO `forgotpass` SET
`email`='$email',
`code`='$code1'";    
 mysqli_query($dbc,$query_add);
    
 //mail user

$subject = "CIPM STUDY Password Reset";
$message = '
Hi '.$name.',
<br/>
<br/>
<a href="'.$site_root.'reset.php?code='.$code1.'&user='.$email.'">Click here to reset your password</a><br/>
or Copy this link into your browser address press enter <br/>
<a href="'.$site_root.'reset.php?code='.$code1.'&user='.$email.'">'.$site_root.'reset.php?code='.$code1.'&user='.$email.'</a>
';
@$send_mail = mail_user($email,$name,$subject,$message);

if($send_mail==1){
$error_details = 'Mail sent!';

}else{
$error_details = 'Mail NOT sent!';

}

$data = array('status'=>1,'details'=>"$error_details"); 
$response = json_encode($data);
echo $response;
}
// end if get    
}
else{

$data = array('status'=>0,'details'=>"Integration error, please input email address");
$response = json_encode($data);
echo $response ;

}

?>