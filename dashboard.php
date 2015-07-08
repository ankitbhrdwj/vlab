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
<title>Instructor Dashboard</title>
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
		<li><div id="button"><a href="instructor.php" >Home</a></div></li>	
		<li><div id="button"><a href="addcourse.php" >Add Course</a></div></li>
		<li><div id="button"><a href="addstudent.php" >Add Students</a></div></li>
		<li><div id="button"><a href="template.php" >Select Template</a></div></li>
		<li><div id="button"><a href="dashboard.php" class="active">Dashborad</a></div></li>
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
	<div id="side3">
		<pre>Courses Offered</pre>
		<pre>---------------</pre>
		<form name="form" action="dashboard.php" method="post">
		<?php
			$result=mysql_query("select * from course_info where instructor like '$name';") or die (mysql_error());
			if(mysql_num_rows($result) > 0)
			{
				while(list($instructor, $courseid, $template) = mysql_fetch_row($result))
	   			{
					echo "<input type=\"submit\" name=\"submit\" value=\"$courseid\">"."<br>";
				}
			}
			else{ echo "No course is offered by you";}
		?>
		</form>
	</div>
	<div id="side4">
		<pre>Students Enrolled</pre>
		<pre>-----------------</pre>
		<?php
			if(isset($_POST['submit']))
			{	
				echo "<table align=center border=1>";
				$course=$_POST['submit'];
				$result1=mysql_query("select * from enrollment_info where courseid like '$course';") or die (mysql_error());
				if(mysql_num_rows($result1) > 0)
				{
				      	while($row = mysql_fetch_array($result1))
				      	{
						echo "<tr><td>".$row['rollno']."</td></tr>";
				      	}
				}
				if(mysql_num_rows($result1) == 0){ echo "No student is enrolled in this course !!!";}
				echo "</table>";
			}
		?>
	</div>
	<?php
		$result=mysql_query("select * from course_info where instructor like '$name';") or die (mysql_error());
		$num=mysql_num_rows($result);
		$num1=0;
		$num2=0;
		while(list($instructor, $courseid, $template) = mysql_fetch_row($result))
		{
		   $result1=mysql_query("select * from enrollment_info where courseid like '$courseid';") or die (mysql_error());
		   $num1=$num1+mysql_num_rows($result1);
		   $result2=mysql_query("select * from vm_info where status='1' && courseid like '$courseid';") or die (mysql_error());	
		   $num2=$num2+mysql_num_rows($result2);	
		   $command="sudo virsh -c qemu:///system list | /bin/grep $courseid | /usr/bin/wc -l 2>&1";	
		   $output = shell_exec($command);
		}
		echo "<div id=\"circle\"><h2>$num</h1><br><br><br>Total Offered Courses</div>";
		echo "<div id=\"circle\"><h2>$num1</h1><br><br><br>Total Enrolled Student</div>";
		echo "<div id=\"circle\"><h2>$num2</h1><br><br><br>Total Created VMs</div>";
		echo "<div id=\"circle\"><h2>$output</h1><br><br><br>Total Running VMs</div>";	
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
</body>
</html>
