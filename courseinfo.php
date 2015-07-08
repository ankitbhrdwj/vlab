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
$login->connect();
?>
<html>
<head>
<title>Create VMs</title>
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
		<li><div id="button"><a href="admin.php" >Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" >Add-Admin</a></div></li>
		<li><div id="button"><a href="/vlab/listing/" >Templates</a></div></li>
		<li><div id="button"><a href="createvm.php" >Course Info</a></div></li>
	<!--	<li><div id="button"><a href="createvm.php" >Create VMs</a></div></li> -->
		<li><div id="button"><a href="dashboard_admin.php" >Dashboard</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
			<li><div id="logout"><a href="change.php" class="active"><b>Password Change</b></a></div></li>
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
<div id="main">
	<div id="side1">
		<pre><b>Courses information</b></pre>			
		++++++++++++++
		<br><br>
		<?php
			echo "<pre>Course-id   Instructor</pre>";
			$result=mysql_query("Select * from course_info");
			if(mysql_num_rows($result) == 0)
			{	
				echo "No course is added to system";
			}
			while($row = mysql_fetch_array($result))
   			{
				echo "<pre>".$row['courseid']."     ";
				echo $row['instructor']."</pre>";
			}
		?>	
	</div>
	<div id="side2">
		<pre>Course-Wise VM Created </pre>
		<form name="form1" id="customForm" action="createvm.php" method="post">
		CourseID: <input type="text" name="courseid" > 
		<input type="submit" id="submit" name="submit" value="List-of-VMs"/>
		</form><br>
		<?php
			if(isset($_POST['submit']) && $_POST['courseid']!="")
			{
				$flag=0;
				$courseid=$_POST['courseid'];
				$result=mysql_query("Select vmname from vm_info where courseid='$courseid' and status='1'");
				if(mysql_num_rows($result) == 0)
				{	
					echo "No VM is added for this course";
				}
				else
				{
					echo "<pre><b>VMs created</b><br>++++++++++</pre><br>";
					while($row = mysql_fetch_array($result))
   					{
						echo $row['vmname']."<br>";
					}
				}
			}
		?>	
	</div>
	<div id="side3">
		<pre>Course-Wise Pending VMs</pre>
		<form name="form2" id="customForm" action="createvm.php" method="post">
		CourseID: <input type="text" name="courseID"> 
		<input type="submit" id="submit1" name="submit1" value="List-of-VMs"/>
		</form><br>
		<?php
			if(isset($_POST['submit1']) && $_POST['courseID']!="")
			{
				$courseid=$_POST['courseID'];
				$result=mysql_query("Select * from enrollment_info where courseid='$courseid'");
				if(mysql_num_rows($result) == 0)
				{	
					echo "No student enrolled in this course !!!";
				}
				else
				{
					echo "<pre><b>VMs to create</b><br>++++++++++++++</pre>";
					$result=mysql_query("Select vmname from vm_info where courseid='$courseid' and status='0'");
					if(mysql_num_rows($result) == 0)
					{	
						echo "No pending VM in this course";
					}
					else
					{
						while($row = mysql_fetch_array($result))
	   					{
							echo $row['vmname']."<br>";
						}
					}
				}
			}
		?>
	</div>
	<div id="side4">
		<pre>Course-Wise Enrollment List</pre><br>
		<form name="form2" id="customForm" action="createvm.php" method="post">
		CourseID: <input type="text" name="courseID"> 
		<input type="submit" id="submit2" name="submit2" value="List-of-Students"/>
		</form><br>
		<?php
			if(isset($_POST['submit2']) && $_POST['courseID']!="")
			{
				$courseid=$_POST['courseID'];
				$result=mysql_query("Select * from enrollment_info where courseid='$courseid'");
				if(mysql_num_rows($result) == 0)
				{	
					echo "No student enrolled in this course !!!";
				}
				else
				{
					echo "<pre><b>Enrollment List</b><br>++++++++++++++++</pre>";
					while($row = mysql_fetch_array($result))
   					{
						echo $row['rollno']."<br>";
					}
				}
			}
		?>
	</div>	
</div>
</body>
</html>
