<?php
include "login.class";
$login=new login;
if(isset($_POST['submit']) && $_POST['email'] != "")
{
	$email=$_POST['email'];
	$login->forgot_pwd($email);
}
?>
