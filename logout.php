<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
include "login.class";
$login=new login;
$login->destroy_cookie();
$login->sessiondestroy();
?>
