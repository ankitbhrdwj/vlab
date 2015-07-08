<?php
include "login.class";
$login=new login;
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
