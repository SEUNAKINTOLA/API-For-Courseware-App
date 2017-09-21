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
if(isset($_GET['package']) && isset($_GET['user'])){
include_once("system.php");
$email =  $_GET['user'];
//check user
$table_users = 'users';
$query = "SELECT * FROM `$table_users` WHERE `email`='$email'";
$check_result = mysqli_query($dbc,$query);
$user = mysqli_fetch_array($check_result); 
$fn= $user['first_name']; 
$ln= $user['last_name']; 
$name = $fn." ".$ln;
    
//select package
$package = $_GET['package'];
$sql = mysqli_query($dbc,"SELECT * FROM `package` WHERE `package_name`='$package'");
$row = mysqli_fetch_array($sql);
//get the price
$price= $row['package_price']; 
$rand = rand(0,9999);
$now = time();
$site_reference = crypt($now,$rand);
$site_reference1 = $site_reference;
$customer_email = $_GET['user'];
$site_failed_redirect_url = "";
$site_success_redirect_url = '';
$product_code = $package;
$product_description = $package;
$pac = 'ss/setup';
$pack = str_replace('/setup', '', $pac);

$data = array('status'=>1,'price'=>$price,'package'=>$package,'site_reference'=>$site_reference1,'customer_email'=>$customer_email,'name'=>$name);
$response = json_encode($data);
echo $response;
//go to gateway to payment
 
//return to app and update paid and payment tables
// lk9u65header("Location: pay.php");ppppp

}else{
$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

echo $response;
}
?>