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
<title>Admin Information Update Page</title>
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
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php" class="active"> Update Information</a></div></li>
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
	<form name="form2" id="customForm" action="update.php" method="post" style="font:serif;font-size:100%;">
		<table align=center style="font:serif;font-size:100%;">
		<tr><td>Username:</td><td><input type="text" name="username" placeholder="<?php echo $user;?>" size="10" readonly="readonly" color="red"></td></tr>
		<tr><td>Name:</td><td><input type="text" name="name" placeholder="<?php echo $name;?>" size="10"></td></tr>
		<tr><td>Password:</td><td><input type="password" name="password" placeholder="password" size="10"></td></tr>
		<tr><td>Email-ID:</td><td><input type="text" name="email" placeholder="<?php echo $email;?>" size="10"></td></tr>
		</table><br>
		<input type="submit" id="submit" name="submit" value="Update Admin Information"/>
	</form><br>
</div>
<?php
	if(isset($_POST['submit']))
	{
		$name=$_POST['name'];
		$password=$_POST['password'];
		$email=$_POST['email'];
	 	mysql_query("UPDATE user_info SET name='$name',password='$password',email='$email' WHERE username ='admin'");
		$msg  = "Your information has been changed for vLAB.\n";
		$msg .= "Your new login information is:\n\n";
		$msg .= "Username: admin\n";
		$msg .= "Name: $name\n";
		$msg .= "Password: $password\n";
		$msg .= "Email-ID: $email\n";		
		mail($email, "Login Information","$msg", "From:noreply@vlab.cse.iitb.ac.in");
		echo  "<script>alert(\"Admin Information Updated\")</script><br>"; 
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
