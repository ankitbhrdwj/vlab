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
<title>Select Template</title>
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
		<li><div id="button"><a href="template.php" class="active">Select Template</a></div></li>
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
<div id="main"  align="center">
<form action=template.php method="POST" style="font:serif;font-size:90%;">
CourseId:
<?php
		$result=mysql_query("Select * from course_info where instructor LIKE '%$name%'") ;
		echo "<select name='courseid' style=\"font:serif;font-size:90%;\">";		
		echo "<option value=''> Select CourseID</option>";
		if($result){		
			while($row = mysql_fetch_array($result))
	   		{
				$cid=$row['courseid'];
				echo "<option value='$cid'>$cid</option>";
			}
			echo "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br><br>";
		}
		else
		{
			echo "<br><p align=\"center\">No course is registered by you.</p>";		
		}
?>
<div style="font:bold serif;font-size:100%;">Select a Template</div>
<div id="mainos" align="center">
	<?php
		$result=mysql_query("Select * from template_info");
		if(mysql_num_rows($result) != 0 )
		{
			while($row= mysql_fetch_array($result))
			{
				$template=$row['templatename'];
				$os=$row['os'];
				$uname=$row['username'];
				$passwd=$row['password'];
				echo "<div id=\"tempos\" align=left style=\"font-size:70%;\"><div id=\"cbox\"><input type='radio' name=os id=$template value=$template align=right></div><div id=ctext><b>$os</b><br>username: 					<i>$uname</i>, password: <i>$passwd</i></div></div>";
			}
		}
		else{
			echo  "<script>alert(\"No template, Contact system admin !!!.\")</script><br>"; 
		}
	?>
	<br>
</div>
	<br>
	<input type="submit" name="submit" value="Select This Template" style="font:serif;font-size:90%;">
	</form>
<?php
	if(isset($_POST['submit']))
	{
			if($_POST['os']!="" && $_POST['courseid']!=""){
				//echo $_POST['os']."\t".$_POST['courseid'];
				$login->connect();
				$course=$_POST['courseid'];
				$template=$_POST['os'];	
				$result=mysql_query("select template from course_info where courseid='$course'");
				$row = mysql_fetch_array($result);
				if($row['template']=='0')
				{
					$result=mysql_query("Update course_info set template='$template' where courseid='$course'");
					if(!$result)
					{
						echo  "<script>alert(\"Error !!! Try Later\")</script><br>";
					}
					else
					{
					//	$src="/vlab/template/".$template;
					//	$dest="/vlab/vm_instances/".$course."/images/";
					//	if( ($fp = popen("/usr/bin/rsync $src $dest &", "r"))) 
					//	{
					//		while( !feof($fp) )
					//		{
					//			echo fread($fp,1024);
					//			flush(); // you have to flush buffer
					//	    	}
					//	    	fclose($fp);
					//	}
						$cmd="qemu-img snapshot -c $course sheepdog:$template 2>&1";
						$out=shell_exec($cmd);
						echo  "<script>alert(\"Template Updated\")</script><br>";
						$log="[".date(DATE_RFC2822)."] TEMPLATE ADDED: $template is used for $course.".PHP_EOL;
						error_log("$log", 3,"stats.log");
					}
				}
				if($row['template']!='0')
				{
					$temp=$row['template'];
					echo  "<script>alert(\"You have already given $temp as a template\")</script><br>";
				}
			}
			else
			{
				echo  "<script>alert(\"Cann't give blank CourseID or Templatename !!!.\")</script><br>";
			}			
	}
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
