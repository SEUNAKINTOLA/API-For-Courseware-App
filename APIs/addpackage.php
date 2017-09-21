<?php
include_once("system.php");

if ( isset($_POST['addpackage']) ) {
    

		$pname = trim($_POST['pname']);
		$pname = strip_tags($pname);
		$pname = htmlspecialchars($pname);

		$pamount = trim($_POST['pamount']);
		$pamount = strip_tags($pamount);
		$pamount = htmlspecialchars($pamount);
    
        $purl = $_POST['purl'];
        $purl = mysqli_real_escape_string($dbc,$purl);
        $error = false;
        // basic name validation
		if (empty($pname)) {
			$error = true;
			$pnameError = "Please enter packagename.";
		} 
    	if( !$error ) {  
       mysqli_query($dbc,"INSERT INTO package(package_name,package_price,folder_location) VALUES('$pname', '$pamount','$purl')");
        }
    }

if ( isset($_POST['addcourse']) ) {
		$cname = trim($_POST['cname']);
		$cname = strip_tags($cname);
		$cname = htmlspecialchars($cname);

		$ccode = trim($_POST['ccode']);
		$ccode = strip_tags($ccode);
		$ccode = htmlspecialchars($ccode);    
        
    
        $packn = trim($_POST['packn']);
		$packn = strip_tags($packn);
		$packn = htmlspecialchars($packn); 
       mysqli_query($dbc,"INSERT INTO courses(course_title,course_code,parent_package_name) VALUES('$cname', '$ccode', '$packn')");
    
    }


	if( isset($_POST['btn-delete']) ) {
        $pack = $_POST['pack'];    
        mysqli_query($dbc,"DELETE FROM package WHERE package_name='$pack'"); 
    } 

	if( isset($_POST['btn-del']) ) {
        $cc = trim($_POST['cc']);    
        mysqli_query($dbc,"DELETE FROM courses WHERE course_code='$cc'"); 
    } 
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

<div id="main" style="padding-left:70px;">
  <h3> Add new package</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <div class="col-md-12">
    <label>Package Name:</label>
    <input name="pname" type="text"></input>    
    <br/>
    <label>Package Amount:</label>
    <input name="pamount" type="text"></input>
    <br/>
    <label>Package Folder:</label>
    <input name="purl" type="text"></input> 
    <br/>
        <div class="form-group">
	<button type="submit" class="btn btn-primary" name="addpackage">Add Package</button>
    </div>
    </div>
    </form>
    <br/>
    <br/>
  
    
     <div  class="span9 padding-mid" style="font-size:20px; border-margin:0px; border-padding:0; overflow-x: auto; cellspacing:0px;">
         <table border="0" cellpadding="0" cellspacing="0"  id="table" class="table table-responsive  table-hover">
                                  <h3> Packages Available</h3> 

             <thead>
                    <tr><th>S.No</th><th>Package Name</th><th>Amount</th><th><div class='noPrint'>action</div></th></tr>
                </thead>
              <?php
                $query=mysqli_query($dbc,"SELECT * FROM package");
                $i= 1;
                while ($row = mysqli_fetch_array($query)) {
                    $packn[$i] =  $row['package_name'];                               
                    $amt[$i]= $row['package_price']; 

                ?>
                <tr>

                    <td><?php echo $i;?></td>
                    <td><?php echo $packn[$i];?></td>
                    <td><?php echo $amt[$i];?></td>
                    <td>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                    <div class="form-group">
                    <input name="pack" type="hidden" value="<?php echo $packn[$i]; ?>">
                    <button type="submit" class="btn btn-block btn-primary" name="btn-delete">Delete</button>
                    </div>

                </form>
                    </td>

                </tr>

                <?php $i= $i+1; } ?>
                <?php $i= 0; ?>
                </table>	
        </div>

<br/>
<br/>
    
    
    
    
    
      <h3> Add new course</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
        <div class="col-md-12">
        <label>Course title:</label>
        <input name="cname" type="text"></input>    
        <br/>
        <label>Course code:</label>
        <input name="ccode" type="text"></input> 
        <br/>
        <select class="form-control"name="packn">
            <?php 
            $sql = mysqli_query($dbc,"SELECT * FROM package");
            while ($row = mysqli_fetch_array($sql)){

            echo "<option value=".$row['package_name'].">" . $row['package_name'] ."</option>";
            }
            ?>
        </select>    
        <button type="submit" class="btn btn-primary" name="addcourse">Add Course</button>
        </div>
    </form>
    <br/>
    <br/> 
         <div  class="span9 padding-mid" style="font-size:20px; border-margin:0px; border-padding:0; overflow-x: auto; cellspacing:0px;">
         <table border="0" cellpadding="0" cellspacing="0"  id="table" class="table table-responsive  table-hover">
                                  <h3> Courses Available</h3> 

             <thead>
                    <tr><th>S.No</th><th>Course Name</th><th>code</th><th>Package</th><th><div class='noPrint'>action</div></th></tr>
                </thead>
              <?php
                $query=mysqli_query($dbc,"SELECT * FROM courses");
                $i= 1;
                while ($row = mysqli_fetch_array($query)) {
                    $coursen[$i] =  $row['course_title'];                               
                    $coursecode[$i]= $row['course_code']; 
                  $packname[$i]= $row['parent_package_name']; 
                ?>
                <tr>

                    <td><?php echo $i;?></td>
                    <td><?php echo $coursen[$i];?></td>
                    <td><?php echo $coursecode[$i];?></td>
                    <td><?php echo $packname[$i];?></td>
                    <td>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                    <div class="form-group">
                    <input name="cc" type="hidden" value="<?php echo $coursecode[$i] ?>">
                    <button type="submit" class="btn btn-block btn-primary" name="btn-del">Delete</button>
                    </div>

                </form>
                    </td>

                </tr>

                <?php $i= $i+1; } ?>
                <?php $i= 0; ?>
            </table>	
        </div>
<br/>
<br/>
         <div  class="span9 padding-mid" style="font-size:20px; border-margin:0px; border-padding:0; overflow-x: auto; cellspacing:0px;">
         <table border="0" cellpadding="16px" cellspacing="10px"  id="table" class="table table-responsive  table-hover">
                                  <h3> Courses Available</h3> 

             <thead >
                    <tr><th>Package</th><th>Course Name</th><th>code</th><th>url</th></tr>
                </thead>
              <?php
                    $allcourses = array();
                    $k=1;
                    
                    $course_contents[$k]= array();
                    $sql = mysqli_query($dbc,"SELECT * FROM `package` WHERE `package_name`='gold'");
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
                ?>
             <?php             for ($i = 1; $i <= count($allcourses); $i++) { ?>
             <tr>
        <td><?php echo $allcourses['packagecontent1']['pname1'];?></td>
        <td><?php echo $allcourses['packagecontent1']['course1'];?></td>
        <td><?php echo $allcourses['packagecontent1']['course_code1'];?></td>
        <td><?php echo $allcourses['packagecontent1']['course_url1'];?></td>

                </tr>
<?php } ?>
            </table>	
        </div>



    </div>
</body>
</html>
<?php ob_end_flush(); ?>