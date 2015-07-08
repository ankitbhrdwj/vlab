<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
$courseid = $_POST['courseid'];
include "login.class";
$login=new login;
$login->connect();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1"/>
<title>Template Uploader</title>
<link rel="stylesheet" type="text/css" href="css/style1.css"/>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font>
	</center>
</div>
<div id="side">
	<ul>
		<li><div id="button"><a href="addstudent.php" >Add Students</a></div></li>
		<li><div id="button"><a href="addcourse.php" >Add Course</a></div></li>
		<li><div id="button"><a href="template.php" class="active">Upload Template</a></div></li>
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
$target_path = "upload/";
$path=basename( $_FILES['uploadedfile']['name']);
$extension = ltrim(strstr($path, '.'), '.');
$filename="template".$courseid.".".$extension;
$target_path = $target_path . $filename;
if(isset($_FILES["uploadedfile"]))
{
	$result=mysql_query("Select * from course_info where courseid='$courseid'") ;
	if(mysql_num_rows($result) == 0)
			{
				echo "<script>alert(\"Course $courseid is not registered.Add course.\")</script><br>";
				echo "<script>location.replace(\"addcourse.php\")</script><br>"; 
			}
	
	shell_exec ("/bin/bash /extras/sync.sh");
	if (file_exists("upload/" . $_FILES["uploadedfile"]["name"]))
	{
	    		echo "File ".'"'.$_FILES["uploadedfile"]["name"].'"'. " already exists. ";
	}
	else
	{
		if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))
		{
			echo "The file ".'"'.$filename.'"'." has been uploaded";
		}
		else
		{
		    echo "There was an error uploading the file, please try again!";
		}
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
