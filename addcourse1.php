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
if(isset($_POST['submit']) && $_POST['courseid'] != "" && $_POST['instname'] != "")
{
	$courseid=$_POST['courseid'];
	$instname=$_POST['instname'];
	$login->add_course($courseid,$instname);			//???? want to change this (courseid,instructor,template,vmuser,vmpass)
}
?>
<html>
<head>
<title>Add Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css"> 
#cover{ position:fixed; top:0; left:0; background:rgba(0,0,0,0.3); z-index:5; width:100%; height:100%; display:none; }
#loginScreen { height:500px; width:500px; margin-left: 35%; position:fixed; z-index:10; display:none; background: rgba(182, 182, 180,0.8) no-repeat; border:5px solid #cccccc; border-radius:10px; }
#loginScreen:target, #loginScreen:target + #cover{ display:block; opacity:20; } 
.cancel { display:block; position:absolute; top:3px; right:2px; background:rgb(245,245,245,1); color:black; height:30px; width:35px; font-size:30px; text-decoration:none; text-align:center; font-weight:bold;} 
</style>

<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
<script src="js/jquery-1.11.2.js"></script>
<script>

$(document).ready(function(){
  $("#add").click(function(){
    $("#loginScreen").load("addcourse.php");
  });
  
$("#adds").click(function(){
    $("#loginScreen").load("addstudent.php");
  });
});
</script>
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
		<li><div id="button"><a href="#loginScreen" class="active">Add Course</a></div></li>	
		<li><div id="button"><a href="addstudent.php" >Add Students</a></div></li>
		<li><div id="button"><a href="template.php" >Upload Template</a></div></li>
		<li><div id="button"><a href="dashboard.php" >Dashborad</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>
			<li><div id="logout"><a href="logout.php" class="active"><b>Logout</b></a></div></li>
		</ul>
	</div>
</div>
<div id="main">
		<div id="loginScreen">
			<a href="addcourse1.php" class="cancel">&times;</a> 
			<button id="But">Add a new course</button>
			<div id="add"><div>
			<div id="template"></div>
			<div id="student"></div>
		</div>
		<div id="cover">
		</div>
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
