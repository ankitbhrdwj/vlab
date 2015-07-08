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
	include "libvirt.class";
	$login=new login;
	$libvirt=new libvirt;
	if($level!="admin"){
		echo "<script>location.replace(\"login.php\")</script><br>";			
	}
?>
<html>
<head>
<title>Delete Course</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/style1.css"/>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript" src="js/script.js"></script>
</head>
</head>
<body id="bg">
<div id="upper">
	<center>
		<font size=4% color="White">vLab:Managing and Provisioning VMs for Labs</font></br><font color="White Gloss"><?php echo "Welcome $name";?></font>
	</center>
</div>
<div id="side">
	<ul>
		<li><div id="button"><a href="admin.php">Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" >Add-Admin</a></div></li>
		<li><div id="button"><a href="/vlab/listing/" >Templates</a></div></li>
		<li><div id="button"><a href="createvm.php" >Course Info</a></div></li>
		<li><div id="button"><a href="dashboard_admin.php" >Dashboard</a></div></li>
		<li><div id="button"><a href="#" id="about">About</a></div></li>
	</ul>
	<div align="center">
		<ul>
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
<div id="main" style="background:transparent;">
<?php
	if(isset($_POST['submit']))
	{
		$username=$_POST['username'];
		$password=$_POST['password'];
		$result=mysql_query("Select * from user_info where username='$username'");
		if(mysql_num_rows($result) == 0)
		{
			echo  "<script>alert(\"User doesn't exists !!!!\")</script>"; 
			echo "<script>location.replace(\"delete.php\")</script><br>";
		}
		while($row1= mysql_fetch_array($result))
		{
			$pwd=$row1['password'];
			$lvl=$row1['level'];
			if($password==$pwd && $level==$lvl)
			{
				echo "<script>location.replace(\"delete.php?option=2\")</script><br>";
				
			}
			else
			{
				echo  "<script>alert(\"Password doesn't match for an $level !!!!\")</script><br>"; 
				echo "<script>location.replace(\"delete.php\")</script><br>";
			}
		}
	}
	$option=null;
	if(isset($_GET['option']))
		$option=$_GET['option'];
	if($option!=2)
	{
		$option=1;
?>
	<div id="strip" style="font:bold;font-size:100%;letter-spacing:1px;text-align:center;color:Yellow;">Verify as an Admin Before Deleting a Course !!!</div>
	<form name="form" id="adminverify" action="delete.php" method="post">
	<input type="textbox" name="username" id="username" placeholder="Username"/><br>
	<input type="password" name="password" id="password" placeholder="Password"/><br>
	<input type="submit" id="submit" name="submit" value="Verify Admin Password"/>
	</form>
<?php
}
if($option==2)
{
	if(isset($_POST['delete']) && $_POST['courseid']!="")
	{
		$courseid=$_POST['courseid'];
		$result=mysql_query("select * from vm_info where courseid='$courseid'");
		while($row = mysql_fetch_array($result))
		{
			$libvirt->deletevm($row['vmname']);
		}
		$command="rm -r /vlab/vm_instances/";	
		$output = shell_exec($command.$courseid);
		//All the VM(s) has been deleted. Now remove stored information;
		$login->delete_course($courseid);
		$log="[".date(DATE_RFC2822)."] DELETE COURSE: $courseid deleted from the system.".PHP_EOL;
		error_log("$log", 3,"stats.log");
		echo "<script>location.replace(\"admin.php\")</script><br>";
	}
	else
	{
		echo  "<script>alert(\"Please think before deleting a course !!!\")</script><br>"; 
		$result=mysql_query("select * from course_info;");
		if(mysql_num_rows($result)!=0)
		{
			echo "<form name='form1' action='delete.php?option=2' method='post'><select name='courseid'>";		
			echo "<option value=''> Select Course-ID</option>";		
			while($row = mysql_fetch_array($result))
	   		{
				$course=$row['courseid'];
				echo "<option value='$course'>$course</option>";
			}
			echo "</select><br><br>";
			echo "<input type='submit' id='delete'  name='delete' value='Delete-Course'>";
			echo "</form>";
		}	
	}
}
?>
</div>
</body>
</html>
