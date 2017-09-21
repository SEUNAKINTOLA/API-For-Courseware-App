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

//get all reqirerd and optional name value
if(!empty($_GET['email'])) {
include("system.php");

//get the parameters for login email 
$email = $_GET['email'];
$email = mysqli_real_escape_string($dbc,$email);

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

$k = 0;
    $b = 0; 
while($k<$i){
    $query3 = "SELECT * FROM `courses`  WHERE  `parent_package_name`='$mypackage[$k]'";
    $result3 = mysqli_query($dbc,$query3) or die("Error checking if Topic exists");
    while($fetched = mysqli_fetch_array($result3)){
    $data+= array(
    "couse_title".$b => $fetched['course_title'],
    "course_code".$b => $fetched['course_code'],
    "course_package".$b => $fetched['parent_package_name']
    );
    $b+=1;
    }    
$k+=1;
}
    
$data+= array('no_of_package'=>$i);
$response = json_encode($data);
echo $response;

}
else{
$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

//echo out json
echo $response;
}

?>