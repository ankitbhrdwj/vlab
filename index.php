<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
include "login.class";
$login = new login;
require "checklogin.php";
{
	$user=$_SESSION['username'];
	$pass=$_SESSION['password'];
	$level=$_SESSION['level'];
	$name=$_SESSION['name'];
	$email=$_SESSION['email'];
?>
<html>
<title>Index</title>
<body bgcolor="Black">
<center><font size=5 color="WhiteGloss">vLab:Managing and Provisioning VMs for Labs</font></center>
<hr>

<?php
}
	//if($level=="fac")
	if($user=="133050022")		// Sushi is instructor
	{
		echo "<script>location.replace(\"instructor.php\")</script><br>";
	}
	else if($level=="admin")
	{
		echo "<script>location.replace(\"admin.php\")</script><br>";
	}
	//if($level=="student")
	else	
	{	
		echo "<script>location.replace(\"student.php\")</script><br>";
	}
?>
</html>
