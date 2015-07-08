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

include "libvirt.class";
$libvirt=new libvirt;
if($level!="admin")
	echo "<script>location.replace(\"$_SESSION[destination]\")</script>";
?>
<html>
<head>
<title>Admin Page</title>
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
		<li><div id="button"><a href="admin.php" class="active">Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" >Add-Admin</a></div></li>
	<!--	<li><div id="button"><a href="/vlab/listing/" >Templates</a></div></li> -->
		<li><div id="button"><a href="cluster.php" >Sheepdog Cluster</a></div></li>
		<li><div id="button"><a href="createvm.php" >Course Info</a></div></li>
	<!--	<li><div id="button"><a href="createvm.php" >Create VMs</a></div></li> -->
		<li><div id="button"><a href="dashboard_admin.php" >Dashboard</a></div></li>
	<!--	<li><div id="button"><a href="delete.php" >Delete Course</a></div></li> -->
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
		<div id="topbutton" style="float:left;"><a href="admin.php">System Information</a></div>
		<div id="topbutton" style="float:left;"><a href="admin.php?option=2">System Status</a></div>
	<!--	<div id="topbutton" style="float:left;"><a href="../vlab/upload/">Enrollment Files</a></div> -->
		<div id="topbutton" style="float:left;"><a href="delete.php">Delete Course</a></div>
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
				$result=mysql_query("select * from course_info");
				echo "<tr><th>#Registered Courses</th><td>: ".mysql_num_rows($result)."</td></tr>";
				$i=1;
				while($row = mysql_fetch_array($result))
   				{
						echo "<tr><th>Course Number $i</th><td>: ".$row['courseid']."</td></tr>";
						echo "<tr><th>Instructor for ".$row['courseid']."</th><td>: ".$row['instructor']."</td></tr>";
						echo "<tr><th>Template for ".$row['courseid']."</th><td>: ".$row['template']."</td></tr>";
						$i+=1;
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
				$result=mysql_query("select * from enrollment_info");
				echo "<tr><th>#Students Enrolled</th><td>: ".mysql_num_rows($result)."</td></tr>";
				$result=mysql_query("select * from course_info");
				while($row = mysql_fetch_array($result))
   				{
						$course=$row['courseid'];
						$result1=mysql_query("select * from enrollment_info where courseid='$course'");
						echo "<tr><th>#Students Enrolled in ".$row['courseid']."</th><td>: ".mysql_num_rows($result1)."</td></tr>";
						$result1=mysql_query("select * from vm_info where status='1' and courseid='$course'");
						echo "<tr><th>#VMs Created for ".$row['courseid']."</th><td>: ".mysql_num_rows($result1)."</td></tr>";
				}
			?>
			</table>
		</div>
	</div>
	<div id="topwidget" style="float:left;">
		<div id="topstrip">
			Template Information
			<button type="button" class="primary">-</button>
		</div>
		<div id="topwidget-main">
			<table style="font:serif;font-size:80%;text-align:left;">
			<?php 
				$i=1;
				$result=mysql_query("select distinct * from template_info");
				while($row = mysql_fetch_array($result))
   				{
						echo "<tr><th>Template #$i</th><td>: ".$row['templatename']."</td></tr>";
						echo "<tr><th>OS in Template#$i </th><td>: ".$row['os']."</td></tr>";
					//	echo "<tr><th>Username </th><td>: ".$row['username']."</td></tr>";
					//	echo "<tr><th>Password </th><td>: ".$row['password']."</td></tr>";
						$i+=1;
				}
			?>
			</table>
		</div>
	</div>
</div>
<?php
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
				echo " <div id=\"strip\" style=\"font:bold serif;font-size:80%;color:black;\">$line </div><br>";
				$no-=1;
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
if($option==3){//Student Info	
?>
<div id="topmain">

</div>
<?php } ?>
</body>
</html>
