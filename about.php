<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
$msg=" vLAB ver. 1.0 \\n Ankit, M.Tech-2 \\n CSE@IIT Bombay";
echo  "<script>alert(\"$msg\")</script><br>"; 
$ref = $_SERVER['HTTP_REFERER'];
echo "<script>location.replace(\"$ref\")</script><br>";
?>

