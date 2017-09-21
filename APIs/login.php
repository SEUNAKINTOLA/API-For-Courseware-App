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

if(isset($_GET['email'])&& isset($_GET['password'])){
include_once("system.php");

//get the parameters for login email and password
$email = $_GET['email'];
$password  = $_GET['password'];
$uuid  = $_GET['uuid'];
$email = mysqli_real_escape_string($dbc,$email);
$password = mysqli_real_escape_string($dbc,$password);
$uuid = mysqli_real_escape_string($dbc,$uuid);
    
//check if user email and password is correct and account status is active
$table_users = 'users';
$query = "SELECT * FROM `$table_users` WHERE `email`='$email' AND `password`='$password' AND`activation_code`=''";
$check_result = mysqli_query($dbc,$query)
or die('Error Querying Database');
$result_num = mysqli_num_rows($check_result);
if($result_num ==0){
//not a valid user 
$data = array('status'=>0,'details'=>"Not a valid user");
$response = json_encode($data);
echo $response ;

}else{
$data = array();  
$i = 0;    
$query2 = "SELECT * FROM `paid` WHERE `owner`='$email'";
$results = mysqli_query($dbc,$query2) or die("Error checking if user exists");
while($fetched = mysqli_fetch_array($results)){
$mypackage[$i] = $fetched['package'];
$package_expiry[$i] = $fetched['expiry'];
$data+= array(
"mypackage".$i => $fetched['package'],
"package_expiry".$i => $fetched['expiry']
);
$i= $i +1;
}

$data+= array('no_of_package'=>$i);

$checkses = "SELECT * FROM `session` WHERE `owner`='$email'";
$checksession = mysqli_query($dbc,$checkses);
$sesrow = mysqli_fetch_array($checksession);
$sesuuid = $sesrow['uuid'];
$num = mysqli_num_rows($checksession);
if($num ==0 || $sesuuid == $uuid ){
//log user in
//login successful
//create a session in session table and mark the expiration to be the next day
$row = mysqli_fetch_array($check_result);
$email = $row['email'];
$phone = $row['phone'];
$status = $row['status'];
$name = $row['first_name'].' '.$row['last_name'];
//det session id and expiration date
$rand = rand(0,9999);
$now = time();
$sessionid = crypt($now,$rand);
$sessionid1 = $sessionid.$email;
$tomorrow = time() + (24 * 60 * 60);
$session_expiration = date('Y-m-d',$tomorrow);
    
//set session variables to db
$query_add = "INSERT INTO `session` SET
`owner`='$email',
`session_expiration`='$session_expiration',
`session_id`='$sessionid1',
`uuid`='$uuid'
";
$result_add = mysqli_query($dbc,$query_add)or die('Error loggin in');


$data+= array('status'=>1,'details'=>"User Exist",'email'=>"$email",'phone'=>"$phone",'stat'=>"$status",'name'=>"$name");
$response = json_encode($data);

echo $response;

} else{
 
 $data = array('status'=>0,'details'=>"This User has an active session");
$response = json_encode($data);
echo $response ;   
}

}
// end if get    
}
else{

$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);
echo $response ;

}

?>