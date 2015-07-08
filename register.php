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
if(isset($_POST['submit']))
{
	$username=$_POST['username'];
	$name=$_POST['name'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$level=$_POST['level'];
	$login->register($username,$name,$password,$email,$level);
}
?>
<html>
<head><title>Register</title><html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<link rel="stylesheet" type="text/css" href="css/register.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/validation.js"></script>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White" style=";">vLab:Managing and Provisioning VMs for Labs</font></br><font color="White Gloss"><?php echo "Welcome $name";?></font>
	</center>
</div>
<div id="side">
	<ul>
		<li><div id="button"><a href="admin.php" >Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" class="active" >Add-Admin</a></div></li>
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
	<div id="container"> 	
		<!--<h1>vLAB Registation</h1>	
		<form name="form2" id="customForm" onsubmit="return validate_all()" action="chek1.php" method="post">-->
			<form name="form2" id="customForm" onsubmit="return validate_all()" action="register.php" method="post" >
			<div>
				<label for="username">Username</label>
				<input type="text" id="username" name="username" placeholder="Username" onChange="search(this.value);" />
				<span id="nameInfo"></span>
			</div>
			<div>
				<label for="name">Name</label>
				<input type="text" id="name" name="name" placeholder="Name"/>
				<span id="usernameInfo"></span>
			</div>

			<div>
				<label for="password">Password</label>
				<input type="password" name="password" id="password" placeholder="Password" onKeyUp="search1(this.value);" />   
				<span>Password Strength: </span><span id="result"></span>
			</div>
			<div>
				<label for="conf_pwd">Confirm Password</label>
				<input type="password" name="conf_pwd" id="conf_pwd" placeholder="Password"onKeyUp="check_password_success();" onBlur="check_password_failure();" />   
				<span>Result </span><span id="match"></span>
			</div>
			<div>
				<label for="email">Email</label>
				<input type="text" id="email" name="email" placeholder="Email-Id" onBlur="validate_email();"/>
				<span id="emailInfo"></span>
			</div>
			<div>
				<label for="level">Level</label>
				<select id="level" name="level" />
				<option value="admin">admin</option>
	  			<!--<option value="instructor">instructor</option>
	  			<option value="student">student</option>-->
				</select>
			</div>
			<div>
				<label for="vercode">Enter Code</label>
				<img src="captcha.php" />
				<input type="text" name="vercode" id="vercode" onBlur="chek_failure(this.value)"; onkeyup="chek(this.value);" size="12"/>
				<span>Image Result: </span><span id="txtvercode"></span>
			</div>
			<div>
				<input id="submit" type="submit" name="submit" value="register"   />
			</div>
	
		</form>
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
