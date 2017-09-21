<?php
if(isset($_GET['email'])&& isset($_GET['password'])){
include_once("system.php");

/*
$data = array('status'=>1,'details'=>"User Exist",'email'=>"mail",'phone'=>"hone",'status'=>"tatus",'name'=>"ame");

$response = json_encode($data);
*/

// select loggedin users detail

$mail = $_GET['email'];
$check=mysqli_query($dbc,"SELECT * FROM `paid` WHERE `owner`='$mail'");
$ckeckpaid=mysqli_fetch_array($check);
$result_num = mysqli_num_rows($check);

    
if($result_num <1 ){

// get the packages subscribed for, replace  $demo with $check and $ckeckdemo with $ckeckpaid
 $demo=mysqli_query($dbc,"SELECT * FROM `package`");

$m =0;  
while ($ckeckdemo=mysqli_fetch_array($demo)) {
    $package[$m] = $ckeckdemo['package_name'];
$m = $m + 1; 
}   
//$package = $ckeckpaid['package'];
for ($c = 0; $c < mysqli_num_rows($demo); $c++) { 
$allcourses = array();
$k=1;
$course_contents[$k]= array();
$sql = mysqli_query($dbc,"SELECT * FROM `package` WHERE `package_name`='$package[$c]'");
while ($row = mysqli_fetch_array($sql)){
$pname[$k]= $row['package_name']; 
$purl[$k]= $row['folder_location'];

// get the courses under each package
$i=1;
$sq = mysqli_query($dbc,"SELECT * FROM `courses` WHERE `parent_package_name`='$pname[$k]'" );
while($ro = mysqli_fetch_array($sq)){
$course[$i]= $ro['course_title'];
$course_url[$i]= $purl[$k].'/'.$course[$i].'.txt';
$course_code[$i]= $ro['course_code'];
$course_content[$i] = array('course'.$i=>$course[$i],'course_url'.$i=>$course_url[$i],'course_code'.$i=>$course_code[$i],'pname'.$i=>$pname[$k]);
$course_contents[$k]=  array_merge($course_contents[$k],$course_content[$i]);
$i= $i+1;
}

$packagecontent[$k]= array('packagecontent'.$k=>$course_contents[$k]);
$allcourses = array_merge($allcourses,$packagecontent[$k]);
$k=$k+1;

}
    
    
$data = $allcourses;
$response = json_encode($data);
echo $response ;
}  

}
 else{       
echo 'Please purchase a package. Thanks.' ;
    } 
}

else{
    $data = array('status'=>0,'details'=>"Error, user must be logged in");
$response = json_encode($data);
echo $response ;
    } 





//get the users paid courses
//get from paid where email is session email, 
//render the courses on the app

//for each course , render the materail, Check if session is active first  befre rendering 



?>  