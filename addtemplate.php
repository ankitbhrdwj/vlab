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
if($level!="admin")
	echo "<script>location.replace(\"$_SESSION[destination]\")</script>";
?>
<html>
<head>
<title>Add Template</title>
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
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font></br><font color="White Gloss"><?php echo "Welcome $name";?></font>
	</center>
</div>
<div id="side">
	<ul>
		<li><div id="button"><a href="admin.php">Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php" class="active">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" >Add-Admin</a></div></li>
		<!--	<li><div id="button"><a href="/vlab/listing/" >Templates</a></div></li> -->
		<li><div id="button"><a href="cluster.php" >Sheepdog Cluster</a></div></li>
		<li><div id="button"><a href="createvm.php" >Course Info</a></div></li>
	<!--	<li><div id="button"><a href="createvm.php" >Create VMs</a></div></li> -->
		<li><div id="button"><a href="dashboard_admin.php" >Dashboard</a></div></li>
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
	<form enctype="multipart/form-data" action="addtemplate.php" method="POST" id="form" style="font:serif;font-size:100%;">
		<input type="hidden" name="name" id="name"/>
		<table align=center style="font:serif;font-size:100%;">
		<tr><td>Template Name</td><td><input type="file" id="template"/></td></tr>
		<tr><td>Select OS</td><td><select name="os">
			<option value=" ">Select OS</option>
			<option value="Ubuntu 12.04">Ubuntu 12.04</option>
			<option value="Ubuntu 13.10">Ubuntu 13.10</option>
			<option value="Ubuntu 14.04">Ubuntu 14.04</option>
			<option value="Ubuntu Server 12.04">Ubuntu Server 12.04</option> 
			<option value="Ubuntu Server 13.10">Ubuntu Server 13.10</option>  
			<option value="Ubuntu Server 14.04">Ubuntu Server 14.04</option>   
		</select></td></tr>
		<tr><td>User Name: </td><td><input type="text" name="usrname" id="usrname"/></td></tr>
		<tr><td>Password: </td><td><input type="password" name="password"/></td></tr>
		</table><br>
		<input type="submit" name="submit" value="Add New Template"/>
		<input type="submit" name="information" value="Information" onclick="window.open('./documents/info.pdf')"/><br><pre> </pre>
	</form>
	<script type="text/javascript">
		$(function(){
			$('#template').change(function(){
				var file = document.getElementById('template').value;
				document.getElementById('name').value = file;
			}); 
	
			$("form").submit(function(){
   			//	$(this).children('#template').remove();
			});
		});
	</script>
</div>
<?php	
	if(isset($_POST['submit']))
	{
		if($_POST["name"]!= "" && $_POST['os']!= "" && $_POST['usrname']!= "" && $_POST['password']!= "")
 		{	
			//echo $_POST['name']."<br>".$_POST['os']."<br>".$_POST['usrname']."<br>".$_POST['password'];	
			$login->add_template($_POST['name'],$_POST['os'],$_POST['usrname'],$_POST['password']);
		}	
		else
		{
			echo "<script>alert(\"Fill each field properly.\")</script><br>";
		}
	}
?>
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
