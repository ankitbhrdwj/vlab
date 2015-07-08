<?php
include "login.class";
$login=new login;
$login->connect();
$password=$_GET["q"];
$login->chek_pwd($password);
?>
