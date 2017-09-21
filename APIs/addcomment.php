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

if(!empty($_GET['forumID']) && !empty($_GET['comment']) && $_GET['comment'] != "undefined" && !empty($_GET['email']) ){
include("system.php");


// get post parameters
$forumID = $_GET['forumID'];
$email = $_GET['email'];
$comment = $_GET['comment'];

// get details of the topic add comment to    
$query = "SELECT * FROM `forum_db` WHERE `forumID`='$forumID'";
$result = mysqli_query($dbc,$query) or die("Error checking if Topic exists1");
$fetch = mysqli_fetch_array($result);

// get parameters from forum_db
$coursecode = $fetch['coursecode'];
$title = $fetch['Title'];
$question = $fetch['Question'];
$topic_by = $fetch['created_by'];
$package = $fetch['package'];
$time_created = $fetch['time'];


// set comment paramenters
$rand = rand(0,9999);
$now = time();
$commentID = crypt($now,$rand);
$commentID1 = $commentID;

//secure and validate the data colected
$coursecode = mysqli_real_escape_string($dbc, $coursecode);
$title = mysqli_real_escape_string($dbc,$title);
$comment = mysqli_real_escape_string($dbc,$comment);
$topic_by = mysqli_real_escape_string($dbc,$topic_by);
$question = mysqli_real_escape_string($dbc,$question);
$package = mysqli_real_escape_string($dbc,$package);

$query2 = "SELECT * FROM `comments_table`";
//  WHERE  `package`='$package'
$i = 0;
$results = mysqli_query($dbc,$query2) or die("Error checking if Topic exists");
$comments[] = array();
while($fetched = mysqli_fetch_array($results)){
$comments[$i] =  $fetched['comment'];
$i= $i +1;
}


$query_add = "INSERT INTO `comments_table` SET
`coursecode`='$coursecode',
`Title`='$title',
`created_by`='$topic_by',
`Question`='$question',
`package`='$package',
`commentID`='$commentID1',
`comment_by`='$email',
`comment`='$comment',
`time_created`='$time_created',
`forumID`='$forumID'
";
   
$result_add = mysqli_query($dbc,$query_add)or die('Error adding new comment');
if($result_add){
//Comment successfully added

$data = array('status'=>1,'details'=>"Comment added",'forumID'=>"$forumID");
$data = array_merge($data, $comments);

}else{
//failure comment not added
$data = array('status'=>0,'details'=>"Request Failed retry");
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