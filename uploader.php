<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
$courseid = $_POST['courseid'];
include "login.class";
$login=new login;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<title>Upload Student Information</title>
<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font>
	</center>
</div>
<div id="side">
	<ul>
		<li><div id="button"><a href="instructor.php" >Home</a></div></li>
		<li><div id="button"><a href="addcourse.php" >Add Course</a></div></li>
		<li><div id="button"><a href="addstudent.php" class="active">Add Students</a></div></li>
		<li><div id="button"><a href="template.php" >Upload Template</a></div></li>
		<li><div id="button"><a href="dashboard.php" >Dashborad</a></div></li>
		<li><div id="button"><a href="about.php">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>
			<li><div id="logout"><a href="logout.php" class="active"><b>Logout</b></a></div></li>
		</ul>
	</div>
</div>
<div id="main">
<?php 
$csv_mimetypes = array(
    'text/csv',
    'text/plain',
    'application/csv',
    'text/comma-separated-values',
    'application/excel',
    'application/vnd.ms-excel',
    'application/vnd.msexcel',
    'text/anytext',
    'application/octet-stream',
    'application/txt',
);

$target_path = "upload/";
$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
if(isset($_FILES["uploadedfile"]))
{
	if($courseid!=""){
		$result=mysql_query("Select * from course_info where courseid='$courseid'") ;
			if(mysql_num_rows($result) == 0)
			{
				echo "<script>alert(\"Course $courseid is not registered.\")</script><br>";
				echo "<script>location.replace(\"addcourse.php\")</script><br>"; 
			}
	
	if (in_array($_FILES['uploadedfile']['type'], $csv_mimetypes)) 
	{
		if (file_exists("upload/" . $_FILES["uploadedfile"]["name"]))
		{
	    		echo "File ".'"'.$_FILES["uploadedfile"]["name"].'"'. " already exists. Please rename the file. ";
		}
		else
		{
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
			{
				echo "The file ".'"'.basename( $_FILES['uploadedfile']['name']).'"'." has been uploaded<br>";
	
				/*Read csv file and insert in the database*/
				$CSVfp = fopen("$target_path", "r");
				if($CSVfp !== FALSE) {
					while(! feof($CSVfp)) {
						$data = fgetcsv($CSVfp, 1000, ",");
						$username = $data[0];
						//$name = $data[1];
						//$password = $data[2]; 
						//$email = $data[3];
						//$level = $data[4];
						//echo $username." ".$name." ".$password." ".$email." ".$level."<br>";	
						if($username!="" && $courseid!="")//&& $name!="" && $password!="" && $email!="" && $level!="")
						{
							//$login->register($username,$name,$password,$email,$level);
							$login->enroll($username,$courseid);
							$login->createvm($username,$courseid);
						}
					}
				}
				fclose($CSVfp);
				$log="[".date(DATE_RFC2822)."] STUDENTS ADDED: Students added to $courseid using file ".basename( $_FILES['uploadedfile']['name'])."".PHP_EOL;
				error_log("$log", 3,"stats.log");
				//unlink($target_path);
				echo "<script>location.replace(\"template.php\")</script><br>"; 		
			}
			else
			{
			    echo "There was an error uploading the file, please try again!";
			}
		}
	}
	else
	{
		echo "<script>alert(\"Unacceptable file type..\")</script><br>";
		echo "<script>location.replace(\"addstudent.php\")</script><br>"; 	
	}
	}
	else
	{
		echo "<script>alert(\"You cannot give an empty CourseId.\")</script><br>";
		echo "<script>location.replace(\"addstudent.php\")</script><br>"; 	
	}
}
else
{
	echo "No file is selected to upload.";
}
?>
</div>
</body>
</html>
