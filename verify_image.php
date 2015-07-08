<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
include "login.class";
$login=new login;
$capcha=$_GET["q"];
$login->verify_im($capcha);
?>
