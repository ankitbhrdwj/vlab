<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
if (!isset($_SESSION['username'])) 
{
	$_SESSION['destination']=$_SERVER["REQUEST_URI"];
	header("Location:login.php");
	exit(0);
}
?>
