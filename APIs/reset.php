<?php
//if(isset($_GET['code'])  && isset($_GET['user'])) {
include_once("system.php");
if(isset($_GET['code'])  && isset($_GET['user'])) {
$code = $_GET['code'];
$user = $_GET['user'];
}
if ( isset($_POST['btn-update']) ) {
$newpass = $_POST['pass'];
$code1 = $_POST['code'];
$user1 = $_POST['user'];
    
$query = "SELECT * FROM `forgotpass` WHERE `email`='$user1' AND `code`='$code1'";
$result = mysqli_query($dbc,$query) or die ('error checking if user exists');
$isithtere =mysqli_num_rows($result);
if($isithtere != 0){
//reset link valid
//reset password or send error as required
    
$query_add = "UPDATE `users` SET
`password`='$newpass' WHERE`email`='$user1'";
mysqli_query($dbc,$query_add);

$query_del = "DELETE FROM `forgotpass` WHERE
`email`='$user1'";
mysqli_query($dbc,$query_del);
    
$data = array('status'=>1,'details'=>"Success");
$response = json_encode($data);
echo $response;

}else{
		//Tampered data
		$data = array('status'=>0,'details'=>"tampered data");
        $response = json_encode($data);
        echo $response;
}
}

?>

<html>
<head>
</head>
    <body>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
            <div class="form-group">
            <span class="glyphicon glyphicon-lock"></span>
            <input type="password" name="pass" class="form-control" placeholder="Enter New Password" maxlength="15" />
            </div>
            <div class="form-group">
            <span class="glyphicon glyphicon-lock"></span>
            <input type="password" name="pass2" class="form-control" placeholder="Retype New Password" maxlength="15" />
            </div>
            <div class="form-group">
            <span class="glyphicon glyphicon-lock"></span>
            <input type="hidden" name="code" class="form-control" value="<?php echo $code; ?>"/>
            </div>
            <div class="form-group">
            <span class="glyphicon glyphicon-lock"></span>
            <input type="hidden" name="user" class="form-control" value="<?php echo $user; ?>"/>
            </div>
            <button type="submit" class="btn btn-block btn-primary" name="btn-update">Update</button>
        </form>
    </body>    

</html>