<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
require "checklogin.php";
{
	$user=$_SESSION['username'];
	$pass=$_SESSION['password'];
	$level=$_SESSION['level'];
	$name=$_SESSION['name'];
	$email=$_SESSION['email'];
}
include "login.class";
$login=new login;
?>
<html>
<head>
<title>Student Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>

<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font></br><font color="White Gloss"><?php echo "Welcome $name";?></font>
	</center>
</div>
	
<div id="side">
	<ul>
		<li><div id="button"><a href="student.php" class="active">Home</a></div></li>
	<!--	<li><div id="button"><a href="courses.php" >Courses</a></div></li>-->
		<li><div id="button"><a href="vmstart.php" >VMs</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<!--<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>-->
			<li><div id="logout"><a href="logout.php" class="active"><b>Logout</b></a></div></li>
		</ul>
	</div>
</div>
<div id="main">
<?php
	//$cmd="virsh -c qemu+tcp://10.129.26.29/system version 2>&1";
	//$cmd="virsh -c qemu+tcp://10.196.103.59/system qemu-agent-command 133050024CS101 '{ \"execute\": \"guest-network-get-interfaces\"}' |  /usr/bin/python -m json.tool | /bin/grep -i ip-address | /bin/grep -e 192 -e 10 | /usr/bin/xargs | /usr/bin/cut -d\" \" -f2 | /usr/bin/cut -d\",\" -f1 2>&1";
	//$output=shell_exec($cmd) or print_r(error_get_last());
	//echo "<pre>$output</pre>";

?>
</div>
<script type="text/javascript">
$(function(){
	$('#about').click(function(){
		var unique_id = $.gritter.add({
			// (string | mandatory) the heading of the notification
			title: '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;About vLAB',
			// (string | mandatory) the text inside the notification
			text: '<br><p><strong><font size="6">&nbsp;&nbsp;&nbsp;&nbsp;vLAB</font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.0<br><br></strong></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CSE@IIT Bombay',
			// (string | optional) the image to display on the left
			image: 'images/trees.jpg',
			// (bool | optional) if you want it to fade out on its own or just sit there
			sticky: true,
			// (int | optional) the time you want it to be alive for before fading out
			time: '',
			// (string | optional) the class name you want to apply to that specific message
			class_name: 'my-sticky-class'
		});
		return false;
	});
});
</script>
<div id="main" style="background:transparent;text-align:left;">
	<div id="topmenu">
		<div id="topbutton" style="float:left;margin-left:20%;"><a href="student.php">Enrollment Status</a></div>
		<div id="topbutton" style="float:left;"><a href="student.php?option=2">System Status</a></div>
	</div>
</div>
<?php
$option=null;
	if(isset($_GET['option']))
		$option=$_GET['option'];
	if($option<=1)
	{
		$option=1;	
	}
if($option==1)
{	
	echo "<div id=\"topmain\">";
	$login->enrollment($user);
	echo "</div>";
}
if($option==2){//Student Info
?>
<div id="topmain">
<?php
	//	$a=date(DATE_RFC2822);
	//	$words = explode(" ", $a);
	//	$date=$words[1]." ".$words[2]." ".$words[3];
	//	echo $date."<br>";
	//	echo $words[4].PHP_EOL;
	$no=20;
	$filename="stats.log";
	$command = "tac $filename > reverse.log";
	exec($command);
	ini_set("auto_detect_line_endings", true);
	$handle = fopen("reverse.log", "r");
	if ($handle) 
	{
		echo "<div id=\"stats\">";
		while (($line = fgets($handle)) !== false && $no) 
		{
			if(strlen(strstr($line,$user))>0){
				echo " <div id=\"strip\" style=\"font:14px bold Arial;\">$line </div><br>";$no-=1;}
		}
		fclose($handle);
		echo "</div>";
	}
	else
	{
	    echo "Unable to open the file".PHP_EOL;
	}
	unlink("reverse.log");
?>
</div>
<?php
}	
?>
</body>
</html>
