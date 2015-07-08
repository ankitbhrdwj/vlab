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
?>
<html>
<head>
<title>VM Start</title>
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
		<li><div id="button"><a href="student.php" >Home</a></div></li>
	<!--	<li><div id="button"><a href="courses.php" >Courses</a></div></li>-->
		<li><div id="button"><a href="vmstart.php" class="active">VMs</a></div></li>
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
<!--<div id="main">
	<?php 
		$login->vmstart($user);
	?>
</div>
-->
<?php
//virsh send-key VMNAME KEY_LEFTALT KEY_LEFTCTRL KEY_DELETE

//						CREATE VM
	if(isset($_POST['create']) && $_POST['create'] != "")
	{
		$vmname=$_POST['create'];
		$vmname=trim($vmname);
		$user=trim($user);
		if(strlen(strstr($vmname,$user))>0)
		{
			$result=mysql_query("Select status from vm_info where vmname='$vmname'");
			if(mysql_num_rows($result) != 0)
			{
				$row = mysql_fetch_array($result);
				if($row['status']==1){
					echo "<script>alert(\"You have already created the VM, cann't create again !!!\")</script>";	
				}
				else			// Actually VM is created here 
				{
					$libvirt->create_instance($vmname);
					$log="[".date(DATE_RFC2822)."] VM CREATE: $user Created VM $vmname".PHP_EOL;
					error_log("$log", 3,"stats.log");
				}
			}
		}
		else
		{
			echo "<script>alert(\"No such VM for you.\")</script>";	
		}

	}
	if(isset($_POST['create']) && $_POST['create'] == "")
	{
		echo "<script>alert(\"You cannot give blank VM Name\")</script>";
	}	

	//						START VM

	if(isset($_POST['start']) && $_POST['start'] != "")
	{
		$time_start=0;
		$time_end=0;
		$vmname=$_POST['start'];
		$vmname=trim($vmname);
		$user=trim($user);
		if(strlen(strstr($vmname,$user))>0)
		{
			$password="";
			$username="";
			$ip="";
			$result=mysql_query("select template_info.username,template_info.password from template_info,course_info where template_info.templatename=course_info.template && course_info.courseid in (SELECT courseid from vm_info where vmname='$vmname')");
			if(mysql_num_rows($result)>=0)
			{	
				$row = mysql_fetch_array($result);		
				$username=$row['username'];
				$password=$row['password'];
			}
			$output=$libvirt->domain_start($vmname);
			if($output=="0") {$output=libvirt_get_last_error();}
			if (strpos($output,'started') !== false || strpos($output,'already') !== false) 
			{
				$time_start = microtime(true);
				$ip=$libvirt->domipaddr($vmname);
				$time_end = microtime(true);
				$output.="<br>Use '<b>ssh $username@$ip</b>' to access your Virtual Machine<br>";
				$output.="Default Password for VM was/is <b>\"$password\"</b>. Please change your password.";
				if (strpos($output,'started') !== false)
				{
					$boottime=sprintf('%.2f', $time_end-$time_start);
					$output.="<br>VM took <b>$boottime</b> seconds to boot.";
					$log="[".date(DATE_RFC2822)."] BOOT TIME: $vmname took $boottime seconds to boot.".PHP_EOL;
					error_log("$log", 3,"stats.log");
					$log="[".date(DATE_RFC2822)."] IP Address: $vmname IP Address is $ip.".PHP_EOL;
					error_log("$log", 3,"stats.log");		
				}
				echo "<div id=\"vmstart\">
				<input type='hidden' /> 
	  			<input type='button' value=Close class='removeDiv' style=\"float:right;background-color:Transparent;color:black;border:1px solid #191970;;\"/>
				<pre style='text-align:center'>$output</pre>
	   			</div>";
			}
			if (strpos($output,'not found') !== false) 
			{
				echo "<script>alert(\"VM not created.\")</script>";	
			}
		}
		else
		{
			echo "<script>alert(\"No such VM for you.\")</script>";	
		}

	}

	if(isset($_POST['start']) && $_POST['start'] == "")
	{
		echo "<script>alert(\"You cannot give blank VM Name\")</script>";
	}

	//						STOP VM
	if(isset($_POST['stop']) && $_POST['stop'] != "")
	{
		$vmname=$_POST['stop'];
		$vmname=trim($vmname);
		$user=trim($user);
		if(strlen(strstr($vmname,$user))>0)
		{
			if($libvirt->is_active($vmname)=='1')
			{
				$libvirt->domain_shutdown($vmname);
			}
			else 
			{
				$result = mysql_query("SELECT status from vm_info where vmname='$vmname'");
				$row = mysql_fetch_array($result);	
				$status=$row['status'];
				if ($status == 0) {
					echo "<script>alert(\"VM not created.\")</script>";
				}
				else{
					echo "<script>alert(\"VM is not running.\")</script>";
				}	
			}
		}
		else
		{
			echo "<script>alert(\"No such VM for you.\")</script>";	
		}
	}

	if(isset($_POST['stop']) && $_POST['stop'] == "")
	{
		echo "<script>alert(\"You cannot give blank VM Name\")</script>";
	}
?>
<script type="text/javascript">
$(function () {
    $('.removeDiv').click (function(){ 
	         $(this).parent().remove();
	    });
	});
</script>
<div id="main" style="background:none;">
	<form id="myform" action="vmstart.php" method="post">
	<div id="studentvmlist">
		<div id="heading">
		<select style="font-size:100%;float:right; background-color:Transparent; background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;outline:none;" onchange="myform.submit();" 			name="courseid">
				<option value="">Select a CourseID</option>
				<?php
					$result=mysql_query("Select * from enrollment_info where rollno='$user'") ;	
					if(mysql_num_rows($result) != 0){
						while($row = mysql_fetch_array($result))
		   				{
								echo "<option value=".$row['courseid'].">".$row['courseid']."</option>";
						}
					}
				?>
			</select>
			<b style="float:right;"><pre>  OR  </pre></b>	
			<select style="float:right; font-size:100%;background-color:Transparent; background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;outline:none;" 
			onchange="myform.submit();" name="type">
				<option value="">Select Type</option>
				<option value="ALL">List All VMs</option>
				<option value="ACTIVE">Active VMs</option>
				<option value="INACTIVE">Inactive VMs</option>
			</select>
			<pre style="float:right;">Filter By: </pre>			
		</div>
		<div id="listing">
			<a style="float:left;margin-left:8%;font:Arial;font-size:80%;">VM Name</a>
			<a style="margin-left:14%;font:Arial;font-size:80%;">CreateVM</a>
			<a style="margin-left:15%;font:Arial;font-size:80%;">VM Info</a>
			<a style="margin-left:18%;font:Arial;font-size:80%;">Start/Shutdown VM</a>
			<?php
			if( ((isset($_POST['type']) && $_POST['type']=='ALL') || empty($_POST['type'])) && (empty($_POST['courseid'])) ){ // All VMs
				$result=mysql_query("Select * from vm_info where vmname LIKE '$user%'") ;	
				while($list = mysql_fetch_array($result))
   				{	
				echo "<div id=\"uppervm\">";
				echo "<div id=\"vmname\">".$list['vmname']."</div>";
				echo "<div id=\"vmscreen\">";
				$vmname=$list['vmname'];			
				echo "<button type=\"submit\" name=\"create\" value=\"$vmname\"><img src=\"images/createvm.png\" alt=\"CreateVM\" style=\"width:50%;height:100%;margin:0pt;\"></		
				button>";			
				echo "</div>";
				echo "<div id=\"vminfo\">";
				$libvirt->student_domain_info($list['vmname']);
				echo "</div><div id=\"vmop\">";
				echo "<button type=\"submit\" name=\"start\" value=\"$vmname\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
				echo "<button type=\"submit\" name=\"stop\" value=\"$vmname\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
				echo "</div></div>";
				}
			}

			if(isset($_POST['type']) && $_POST['type']=='ACTIVE'){		//ACTIVE
			$result=mysql_query("Select * from vm_info where vmname LIKE '$user%'") ;	
			while($list = mysql_fetch_array($result))
   			{
				if(libvirt_domain_lookup_by_name($libvirt->conn,$list['vmname'])!=0){
				if($libvirt->is_active($list['vmname'])){
				echo "<div id=\"uppervm\">";
				echo "<div id=\"vmname\">".$list['vmname']."</div>";
				echo "<div id=\"vmscreen\">";
				$vmname=$list['vmname'];			
				echo "<button type=\"submit\" name=\"create\" value=\"$vmname\"><img src=\"images/createvm.png\" alt=\"CreateVM\" style=\"width:55%;height:100%;\"></		
				button>";			
				echo "</div>";
				echo "<div id=\"vminfo\">";
				$libvirt->student_domain_info($list['vmname']);
				echo "</div><div id=\"vmop\">";
				echo "<button type=\"submit\" name=\"start\" value=\"$vmname\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
				echo "<button type=\"submit\" name=\"stop\" value=\"$vmname\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
				echo "</div></div>";
			}}}}

			if(isset($_POST['type']) && ($_POST['type']=='INACTIVE')){
			$result=mysql_query("Select * from vm_info where vmname LIKE '$user%'") ;	
			while($list = mysql_fetch_array($result)){
				if(libvirt_domain_lookup_by_name($libvirt->conn,$list['vmname'])!=0){
				if(!$libvirt->is_active($list['vmname'])){
				echo "<div id=\"uppervm\">";
				echo "<div id=\"vmname\">".$list['vmname']."</div>";
				echo "<div id=\"vmscreen\">";
				$vmname=$list['vmname'];			
				echo "<button type=\"submit\" name=\"create\" value=\"$vmname\"><img src=\"images/createvm.png\" alt=\"CreateVM\" style=\"width:55%;height:100%;\"></		
				button>";			
				echo "</div>";
				echo "<div id=\"vminfo\">";
				$libvirt->student_domain_info($list['vmname']);
				echo "</div><div id=\"vmop\">";
				echo "<button type=\"submit\" name=\"start\" value=\"$vmname\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
				echo "<button type=\"submit\" name=\"stop\" value=\"$vmname\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
				echo "</div></div>";				
			}}}}
			
			if(isset($_POST['courseid']))
			{
				$courseid=$_POST['courseid'];
				$result=mysql_query("Select * from vm_info where vmname LIKE '$user%'") ;	
				while($list = mysql_fetch_array($result)){
				if(strlen(strstr($list['vmname'],$courseid))>0){
				echo "<div id=\"uppervm\">";
				echo "<div id=\"vmname\">".$list['vmname']."</div>";
				echo "<div id=\"vmscreen\">";
				$vmname=$list['vmname'];			
				echo "<button type=\"submit\" name=\"create\" value=\"$vmname\"><img src=\"images/createvm.png\" alt=\"CreateVM\" style=\"width:55%;height:100%;\"></		
				button>";			
				echo "</div>";
				echo "<div id=\"vminfo\">";
				$libvirt->student_domain_info($list['vmname']);
				echo "</div><div id=\"vmop\">";
				echo "<button type=\"submit\" name=\"start\" value=\"$vmname\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
				echo "<button type=\"submit\" name=\"stop\" value=\"$vmname\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
				echo "</div></div>";
				}}
			}
				
		?>
	</div>
	</div>
	</form>
	</div>
</div>
</body>
</html>
