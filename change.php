<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
require "checklogin.php";
{
$user=$_SESSION['username'];
$pass=$_SESSION['password'];
}
?>
<html>
<head>
<title>
Change password
</title>
<link rel="stylesheet" href="css/general.css" type="text/css" media="screen" /> 
</head>
<body>
<div id="upper">
	<font color="WhiteGloss"><b>vLab:Managing and Provisioning VMs for Labs</b></font>
</div>
<div id="container"> 
	<h1>Change Password</h1> 
				
				<form name="form" id="customForm" action="change.php?" method="post">
				
				<div>
			    <label for="username">Username</label>
                <input type="textbox" id="username" name="user" />
				</div>
				
				<div>
				<label for="old">Old Password</label>
				<input type="password" name="old" id="old" />
				</div>
				
				<div>
				<label for="old">New Password</label>
				<input type="password" name="new" id="new" />
				</div>
                
				<div>
				<label for="conf_pwd">Confirm New Password</label>
				<input type="password" name="cnew" id="conf_pwd" />
				</div>
                
				<div>
				<input type="submit" name="submit" value="CHANGE"/>&nbsp&nbsp
				<input type="reset" name="reset" value="RESET"/>
				</div>
</form>
</div>
<?php
if(isset($_POST['submit']))
{
include "login.class";
$login=new login;
$user=$_POST['user'];
$old_pwd=$_POST['old'];
$new_pwd=$_POST['new'];
$cnew_pwd=$_POST['cnew'];
$login->change($user,$old_pwd,$new_pwd,$cnew_pwd);
} 
?>
