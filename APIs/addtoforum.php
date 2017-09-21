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

if(!empty($_GET['coursecode']) && !empty($_GET['title']) && !empty($_GET['email']) && !empty($_GET['question']) && !empty($_GET['package'])){
include("system.php");
$coursecode = $_GET['coursecode'];
$title = $_GET['title'];
$question = $_GET['question'];
$email = $_GET['email'];
$package = $_GET['package'];

// set forum paramenters

$rand = rand(0,9999);
$now = time();
$forumID = crypt($now,$rand);
$forumID1 = $forumID;

//secure and validate the data colected

$coursecode = mysqli_real_escape_string($dbc, $coursecode);
$title = mysqli_real_escape_string($dbc,$title);
$email = mysqli_real_escape_string($dbc,$email);
$question = mysqli_real_escape_string($dbc,$question);
$package = mysqli_real_escape_string($dbc,$package);

//check if topic already exists

$query = "SELECT * FROM `forum_db` WHERE `Title`='$title'";
$result = mysqli_query($dbc,$query) or die("Error checking if Topic exists1");

$num = mysqli_num_rows($result);

if($num==0){
//add new topic if not exist

$query_add = "INSERT INTO `forum_db` SET
`coursecode`='$coursecode',
`Title`='$title',
`created_by`='$email',
`Question`='$question',
`package`='$package',
`forumID`='$forumID1'
";
$result_add = mysqli_query($dbc,$query_add)or die('Error adding new topic');
if($result_add){
//Topic successfully added

$data = array('status'=>1,'details'=>"Topic added, $error_details",'forumID'=>"$forumID1");

}else{
//failure topic not added
$data = array('status'=>0,'details'=>"Request Failed retry");
}

}else{
//topic exist dont add
$data = array('status'=>0,'details'=>"Topic exists");
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