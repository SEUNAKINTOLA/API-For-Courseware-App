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

if(!empty($_GET['forumID'])){
include("system.php");

// get post parameters
$forumID = $_GET['forumID'];

$query2 = "SELECT * FROM `comments_table`";
//  WHERE  `package`='$package'
$i = 0;
$results = mysqli_query($dbc,$query2) or die("Error checking if Topic exists");
$data = array();
$da = array();
while($fetched = mysqli_fetch_array($results)){
$dat= array(
"comment".$i => $fetched['comment'],
"comment_by".$i => $fetched['comment_by'],
"comment_time".$i => $fetched['comment_time'],
"package".$i => $fetched['package']
);
$comments[$i] =  $fetched['comment'];
$da+= array($i=>$dat);
$i= $i +1;
}

  
$data+= array('status'=>1,'details'=>"Success", 'count'=>$i, 'comments'=> $da);

header('Content-Type: application/json');
$response = json_encode($data);

echo $response ;
}else{

$data = array('status'=>0,'details'=>"Integration error, one or more compulsory fields omitted");
$response = json_encode($data);

//echo out json
echo $response ;

}
?>