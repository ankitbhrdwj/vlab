<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
require "checklogin.php";
{
	$user=$_SESSION['username'];
	$pass=$_SESSION['password'];
	$level=$_SESSION['level'];
	$name=$_SESSION['name'];
	$email=$_SESSION['email'];
}
include "login.class";
$login=new login;
if(isset($_POST['submit']) && $_POST['courseid'] != "" && $name !="") //$_POST['instname'] != "")
{
	$courseid=$_POST['courseid'];
	$instname=$name;//$_POST['instname'];
	$login->add_course($courseid,$instname);			//???? want to change this (courseid,instructor,template)
}
?>
<html>
<head>
<title>Add Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font></br><font color="White Gloss"><?php echo"Welcome $name";?></font>
	</center>
</div>

<div id="side">
	<ul>
		<li><div id="button"><a href="instructor.php" >Home</a></div></li>	
		<li><div id="button"><a href="addcourse.php" class="active">Add Course</a></div></li>	
		<li><div id="button"><a href="addstudent.php" >Add Students</a></div></li>
		<li><div id="button"><a href="template.php" >Select Template</a></div></li>
		<li><div id="button"><a href="dashboard.php" >Dashborad</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<!--<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>-->
			<li><div id="logout"><a href="logout.php" class="active"><b>Logout</b></a></div></li>
		</ul>
	</div>
</div>
<div id="main">
	<a style="font:serif;font-size:80%;">Add a new course</a><br>
	<form name="form2" id="customForm" action="addcourse.php" method="post" style="font:serif;font-size:90%;">
		&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp CourseId:   <input type="text" name="courseid">ex:-CS101<br> 
		<!--Instructor name: <input type="text" name="instname">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<br>-->
		<input type="submit" id="submit" name="submit" value="Add Course"/>
		<input type="submit" id="submit" name="submit" value="Instructions"/>
	</form></i>
</div>
<script type="text/javascript">
$(function(){
	$('#about').click(function(){
		var unique_id = $.gritter.add({
			// (string | mandatory) the heading of the notification
			title: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About vLAB',
			// (string | mandatory) the text inside the notification
			text: '<br><p><strong><font size="6">&nbsp;&nbsp;&nbsp;&nbsp;vLAB</font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.0<br><br></strong></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CSE@IIT Bombay',
			// (string | optional) the image to display on the left
			image: 'images/trees.jpg',
			// (bool | optional) if you want it to fade out on its own or just sit there
			sticky: true,
			// (int | optional) the time you want it to be alive for before fading out
			time: '',
			// (string | optional) the class name you want to apply to that specific message
			class_name: 'my-sticky-class'
		});
		return false;
	});
});
</script>
</body>
</html>
