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

// get post parameters
$email = $_GET['email'];

// get details of the topic add comment to    
$query = "SELECT * FROM `paid` WHERE `owner`='$email'";
$result = mysqli_query($dbc,$query) or die("Error checking if Topic exists1");
$fetch = mysqli_fetch_array($result);

// get parameters from forum_db
$package = $fetch['package'];
$data = array();
$i = 0;    
// remember to sort out the issue with the first comment not displaying    
$query2 = "SELECT * FROM `forum_db` WHERE  `package`='$package'";
$results = mysqli_query($dbc,$query2) or die("Error checking if Topic exists");
while($fetched = mysqli_fetch_array($results)){

// echo $fetched['Title'];
$coursecode = $fetched['coursecode'];
$title[$i] = $fetched['Title'];
$data+= array(
"title".$i => $fetched['Title'],
"pay".$i => "pay".$i."()",
"forumID".$i => $fetched['forumID'],
"question".$i => $fetched['Question'],
"topic_by".$i => $fetched['created_by'],
"package".$i => $fetched['package'],
"time_created".$i => $fetched['time'],
);
$question[$i] = $fetched['Question'];
$topic_by[$i] = $fetched['created_by'];
$package[$i] = $fetched['package'];
$time_created[$i] = $fetched['time'];
$i+=1;
}

$data+= array('count'=>$i);
$data+= array('status'=>1,'details'=>"Success");
//$data = array_merge($data, $dat);
$response = json_encode($data);

echo $response;

}else{
$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

//echo out json
echo $response;
}

?>