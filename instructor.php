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
include "libvirt.class";
$libvirt=new libvirt;
function read_file($filename)
{
	ini_set("auto_detect_line_endings", true);
	$handle = fopen("$filename", "r");
	if ($handle) 
	{
		while (($line = fgets($handle)) !== false) 
		{
			echo $line."<br>";
		}
		fclose($handle);
	}
	else
	{
	    echo "Unable to open the file".PHP_EOL;
	}
} 
//if(($level!="fac") 
//	echo "<script>location.replace(\"$_SESSION[destination]\")</script>";fac
?>
<html>
<head>
<title>Instructor Page</title>
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
		<li><div id="button"><a href="instructor.php" class="active">Home</a></div></li>
		<li><div id="button"><a href="addcourse.php" >Add Course</a></div></li>	
		<li><div id="button"><a href="addstudent.php" >Add Students</a></div></li>
		<li><div id="button"><a href="template.php" >Select Template</a></div></li>
		<li><div id="button"><a href="dashboard.php" >Dashborad</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<!--<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>-->
			<li><div id="logout"><a href="logout.php" class="active"><b>Logout</b></a></div></li>
		</ul>
	</div>
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
		<div id="topbutton"><a href="instructor.php">Course Information</a></div>
		<div id="topbutton"><a href="instructor.php?option=2">VM Information</a></div>
		<div id="topbutton"><a href="instructor.php?option=3">System Status</a></div>
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
	if($option==1){	//course info
?>
<div id="topmain">		
	<div id="topwidget" style="float:left;">
		<div id="topstrip">
			Registered Course(s)
			<button type="button" class="primary">-</button>
		</div>
		<div id="topwidget-main">
			<table style="font:serif;font-size:80%;text-align:left;">
			<?php 
				echo "<tr><th>Instructor</th><td>: ".$name."</td></tr>";
				$result=mysql_query("select * from course_info where instructor='$name'");
				echo "<tr><th>#Registered Courses</th><td>: ".mysql_num_rows($result)."</td></tr>";
				$i=1;
				while($row = mysql_fetch_array($result))
   				{
						array_push($all_courses,$row['courseid']);
						echo "<tr><th>Course #$i</th><td>: ".$row['courseid']."</td></tr>";
						$i+=1;
				}
			?>
			</table>
		</div>
	</div>
	<div id="topwidget" style="float:left;">
		<div id="topstrip">
			Used Template(s)
			<button type="button" class="primary">-</button>
		</div>
		<div id="topwidget-main">
			<table style="font:serif;font-size:80%;text-align:left;">
			<?php 
				$result=mysql_query("select * from course_info,template_info where template=templatename and instructor='$name'");
				while($row = mysql_fetch_array($result))
   				{
						echo "<tr><th>Template for ".$row['courseid']."</th><td>: ".$row['template']."</td></tr>";
						echo "<tr><th>OS for ".$row['courseid']."</th><td>: ".$row['os']."</td></tr>";
						echo "<tr><th>Username for ".$row['courseid']."</th><td>: ".$row['username']."</td></tr>";
						echo "<tr><th>Password for ".$row['courseid']."</th><td>: ".$row['password']."</td></tr>";
				}
			?>
			</table>
		</div>
	</div>
	<div id="topwidget" style="float:left;">
		<div id="topstrip">
			Enrollment Information
			<button type="button" class="primary">-</button>
		</div>
		<div id="topwidget-main">
			<table style="font:serif;font-size:80%;text-align:left;">
			<?php 
				$result=mysql_query("select * from course_info where instructor='$name'");
				while($row = mysql_fetch_array($result))
   				{
						$course=$row['courseid'];
						$result1=mysql_query("select * from enrollment_info where courseid='$course'");
						echo "<tr><th>#Students Enrolled in ".$row['courseid']."</th><td>: ".mysql_num_rows($result1)."</td></tr>";
				}
			?>
			</table>
		</div>
	</div>
</div>
<?php }if($option==2){//VM Info ?>
<div id=topmain>
<?php
$result=mysql_query("select * from course_info where instructor='$name'");
while($row = mysql_fetch_array($result))
{
	$course=$row['courseid'];
		echo "<div id=\"topwidget\" style=\"float:left;\">";
		echo "<div id=\"topstrip\">";
			echo "VM(s) for $course";
			echo "<button type=\"button\" class=\"primary\">-</button>";
		echo "</div>";
		echo "<div id=\"topwidget-main\">";
			echo "<table style=\"font:serif;font-size:80%;text-align:left;\">";
				$result1=mysql_query("select * from vm_info where courseid='$course'");
				if(mysql_num_rows($result1)==0)
					echo "No student is enrolled in this course<br>";
				while($row = mysql_fetch_array($result1))
				{
						$vm=$row['vmname'];
						$a=$libvirt->is_active($vm) or libvirt_get_last_error();
						if($a)
							echo "<tr><th>VM ".$vm."</th><td>: Running</td></tr>";
						else	
							echo "<tr><th>VM ".$vm."</th><td>: Shutdown</td></tr>";
				}
			echo "</table>";
		echo "</div>";
		echo "</div>";
}
}	
?>
</div>
<?php
if($option==3){//Student Info
?>
<div id="topmain">
<?php
	//	$a=date(DATE_RFC2822);
	//	$words = explode(" ", $a);
	//	$date=$words[1]." ".$words[2]." ".$words[3];
	//	echo $date."<br>";
	//	echo $words[4].PHP_EOL;
	$no=20;
	$all_courses = array();
	$result=mysql_query("select * from course_info where instructor='$name'");
	while($row = mysql_fetch_array($result))
	{
		array_push($all_courses,$row['courseid']);
	}
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
			foreach ($all_courses as $allc) 
			{
				if(strlen(strstr($line,$allc))>0){
					echo " <div id=\"strip\" style=\"font:bold Arial;font-size:80%;\">$line </div><br>";$no-=1;}
			}
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
