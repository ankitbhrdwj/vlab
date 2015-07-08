<?php
include "login.class";
$login=new login;
$login->connect();
$username=$_GET["q"];
$login->chek_avail($username);
?>
