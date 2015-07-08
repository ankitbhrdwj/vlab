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
include "login.class";
$login=new login;
if($level!="admin")
	echo "<script>location.replace(\"$_SESSION[destination]\")</script>";
?>
<html>
<head>
<title>Admin Dashboard</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/jquery.gritter.css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load('jquery', '1.5');</script>
<script type="text/javascript" src="js/jquery.gritter.min.js"></script>
<script type="text/javascript" src="js/jquery-1.11.2.js"></script>
<script type="text/javascript" src="js/bootstrap.min"></script>

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
		<!--	<li><div id="button"><a href="/vlab/listing/" >Templates</a></div></li> -->
		<li><div id="button"><a href="cluster.php" >Sheepdog Cluster</a></div></li>
		<li><div id="button"><a href="createvm.php" >Course Info</a></div></li>
	<!--	<li><div id="button"><a href="createvm.php" >Create VMs</a></div></li> -->
		<li><div id="button"><a href="dashboard_admin.php" class="active">Dashboard</a></div></li>
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
<?php
if(isset($_POST['ON']))
{
	$vmname=$_POST['ON'];
	$vmname=trim($vmname);
	$libvirt->domain_start($vmname);
}

if(isset($_POST['OFF']))
{
	$vmname=$_POST['OFF'];	
	$vmname=trim($vmname);
	$libvirt->domain_shutdown($vmname);	
}
?>
<div id="main" style="background:none;">
	<form id="myform" action="dashboard_admin.php" method="post">
	<div id="vmlist">
		<div id="heading">
		<select style="float:right;font-size:100%; background-color:Transparent; background-repeat:no-repeat;border: none;cursor:pointer;overflow: hidden;outline:none;" onchange="myform.submit();" 			name="courseid">
				<option value="">Select a CourseID</option>
				<?php
					$result=mysql_query("select courseid from course_info;");
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
			<a style="margin-left:8%;font:Arial;font-size:80%;">Screenshot</a>
			<a style="margin-left:15%;font:Arial;font-size:80%;">VM Info</a>
			<a style="margin-left:15%;font:Arial;font-size:80%;">Start/Shutdown VM</a>
			<?php
				if( ((isset($_POST['type']) && $_POST['type']=='ALL') || empty($_POST['type'])) && (empty($_POST['courseid'])) ){
				$list=libvirt_list_domains($libvirt->conn);
				$size=sizeof($list);
				for($i=0;$i<$size;$i++)	{
					echo "<div id=\"uppervm\">";
					echo "<div id=\"vmname\">".$list[$i]."</div>";
					echo "<div id=\"vmscreen\">";
					if($libvirt->is_active($list[$i]))
						echo "<img src=\"images/running.jpg\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";
					else
						echo "<img src=\"images/vm.png\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";						
					echo "</div>";
					echo "<div id=\"vminfo\">";
					$res=libvirt_domain_lookup_by_name($libvirt->conn,$list[$i]) or die(libvirt_get_last_error());
					$info=libvirt_domain_get_info($res) or die(libvirt_get_last_error());
					$result=mysql_query("select template_info.os from template_info,course_info where template_info.templatename=course_info.template && course_info.courseid in 							(SELECT courseid from vm_info where vmname='$list[$i]')");
					$row = mysql_fetch_array($result);	
				//	if(strlen(strstr($row['os'],"Ubuntu"))>0)
				//	{ echo "<img src=\"images/icon-ubuntu.png\" height=95% style=\"float:right;margin-right:10%;\">";}
					echo "Max memory:\t".$info['maxMem']." KB<br>";
					echo "Used Memory:\t".$info['memory']." KB<br>";
					if($info['state']=='5'){ echo "State:\t Shutdown"."<br>";}
					else { echo "State:\t Running"."<br>";}
					echo "CPU(s):\t".$info['nrVirtCpu']."<br>";
					echo "</div><div id=\"vmop\">";
					echo "<button type=\"submit\" name=\"ON\" value=\"$list[$i]\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:21%;\"></button>";
					echo "<button type=\"submit\" name=\"OFF\" value=\"$list[$i]\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
					echo "</div></div>";
				}}
				if(isset($_POST['type']) && $_POST['type']=='ACTIVE'){
				$list=libvirt_list_domains($libvirt->conn);
				$size=sizeof($list);
				for($i=0;$i<$size;$i++)	{
					if($libvirt->is_active($list[$i])){
					echo "<div id=\"uppervm\">";
					echo "<div id=\"vmname\">".$list[$i]."</div>";
					echo "<div id=\"vmscreen\">";
					if($libvirt->is_active($list[$i]))
						echo "<img src=\"images/running.jpg\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";
					else
						echo "<img src=\"images/vm.png\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";						
					echo "</div>";
					echo "<div id=\"vminfo\">";
					$res=libvirt_domain_lookup_by_name($libvirt->conn,$list[$i]) or die(libvirt_get_last_error());
					$info=libvirt_domain_get_info($res) or die(libvirt_get_last_error());
					$result=mysql_query("select template_info.os from template_info,course_info where template_info.templatename=course_info.template && course_info.courseid in 							(SELECT courseid from vm_info where vmname='$list[$i]')");
					$row = mysql_fetch_array($result);	
					echo "Max memory:\t".$info['maxMem']." KB<br>";
					echo "Used Memory:\t".$info['memory']." KB<br>";
					if($info['state']=='5'){ echo "State:\t Shutdown"."<br>";}
					else { echo "State:\t Running"."<br>";}
					echo "CPU(s):\t".$info['nrVirtCpu']."<br>";
					echo "</div><div id=\"vmop\">";
	
					echo "<button type=\"submit\" name=\"ON\" value=\"$list[$i]\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
					echo "<button type=\"submit\" name=\"OFF\" value=\"$list[$i]\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
					echo "</div></div>";
				}}}
				if(isset($_POST['type']) && ($_POST['type']=='INACTIVE')){
				$list=libvirt_list_inactive_domains($libvirt->conn);
				$size=sizeof($list);
				for($i=0;$i<$size;$i++)	{
					echo "<div id=\"uppervm\">";
					echo "<div id=\"vmname\">".$list[$i]."</div>";
					echo "<div id=\"vmscreen\">";
					if($libvirt->is_active($list[$i]))
						echo "<img src=\"images/running.jpg\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";
					else
						echo "<img src=\"images/vm.png\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";						
					echo "</div>";
					echo "<div id=\"vminfo\">";
					$res=libvirt_domain_lookup_by_name($libvirt->conn,$list[$i]) or die(libvirt_get_last_error());
					$info=libvirt_domain_get_info($res) or die(libvirt_get_last_error());
					$result=mysql_query("select template_info.os from template_info,course_info where template_info.templatename=course_info.template && course_info.courseid in 							(SELECT courseid from vm_info where vmname='$list[$i]')");
					$row = mysql_fetch_array($result);	
					echo "Max memory:\t".$info['maxMem']." KB<br>";
					echo "Used Memory:\t".$info['memory']." KB<br>";
					if($info['state']=='5'){ echo "State:\t Shutdown"."<br>";}
					else { echo "State:\t Running"."<br>";}
					echo "CPU(s):\t".$info['nrVirtCpu']."<br>";
					echo "</div><div id=\"vmop\">";
	
					echo "<button type=\"submit\" name=\"ON\" value=\"$list[$i]\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
					echo "<button type=\"submit\" name=\"OFF\" value=\"$list[$i]\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
					echo "</div></div>";				
				}}
				
				if(isset($_POST['courseid']))
				{
					$courseid=$_POST['courseid'];
					$list=libvirt_list_domains($libvirt->conn);
					$size=sizeof($list);
					for($i=0;$i<$size;$i++)	{
						if(strlen(strstr($list[$i],$courseid))>0){
						echo "<div id=\"uppervm\">";
						echo "<div id=\"vmname\">".$list[$i]."</div>";
						echo "<div id=\"vmscreen\">";
						if($libvirt->is_active($list[$i]))
							echo "<img src=\"images/running.jpg\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";
						else
							echo "<img src=\"images/vm.png\" height=95% style=\"border:2px solid gray;border-radius:10px;\">";						
						echo "</div>";
						echo "<div id=\"vminfo\">";
						$res=libvirt_domain_lookup_by_name($libvirt->conn,$list[$i]) or die(libvirt_get_last_error());
						$info=libvirt_domain_get_info($res) or die(libvirt_get_last_error());
						$result=mysql_query("select template_info.os from template_info,course_info where template_info.templatename=course_info.template && course_info.courseid in 							(SELECT courseid from vm_info where vmname='$list[$i]')");
						$row = mysql_fetch_array($result);	
						echo "Max memory:\t".$info['maxMem']." KB<br>";
						echo "Used Memory:\t".$info['memory']." KB<br>";
						if(!$libvirt->is_active($list[$i])){ echo "State:\t Shutdown"."<br>";}
						else { echo "State:\t Running"."<br>";}
						echo "CPU(s):\t".$info['nrVirtCpu']."<br>";
						echo "</div><div id=\"vmop\">";
						echo "<button type=\"submit\" name=\"ON\" value=\"$list[$i]\"><img src=\"images/poweron.png\" alt=\"\" style=\"width:11%;height:10%;\"></button>";
						echo "<button type=\"submit\" name=\"OFF\" value=\"$list[$i]\"><img src=\"images/poweroff.png\" alt=\"\" style=\"width:11%;height:21%\";></button>";
						echo "</div></div>";
					}}
				}
			?>
		</div>
	</div>

	<div id="widget">
		<div id="strip">
			Server Information
			<button type="button" class="primary">-</button>
		</div>
		<div id="widget-main">
			<table style="font:serif;font-size:60%;text-align:left;">
			<?php 
				$flag=1;
				$fh = fopen('/proc/meminfo','r');
				$mem = 0;
				while ($line = fgets($fh)) {
					$pieces = array();
					if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces) && $flag) {
						      $Total = $pieces[1];
						      $Total*=1024;	
						      $flag=0;
					}
					if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
						      $Free = $pieces[1];
					              $Free*=1024;	
						      break;
					}
				 }
				 fclose($fh);
				$info=libvirt_connect_get_information($libvirt->conn);
				echo "<tr><th>Hostname</th><td>: ".$info['hostname']."</td></tr>";
				echo "<tr><th>Hypervisor</th><td>: ".$info['hypervisor_string']."</td></tr>";
				echo "<tr><th>Max VCPU(s) per VM</th><td>: ".$info['hypervisor_maxvcpus']."</td></tr>";
				$info=libvirt_node_get_info($libvirt->conn);
				echo "<tr><th>CPU(s)</th><td>: ".$info['cpus']."</td></tr>";
				echo "<tr><th>Architecture</th><td>: ".$info['model']."</td></tr>";
				$Total=$login->convertfilesize($Total);
				echo "<tr><th>Total Memory</th><td>: ".$Total."</td></tr>";
				$Free=$login->convertfilesize($Free);
				echo "<tr><th>Free Memory</th><td>: ".$Free."</td></tr>";
				$ds = disk_total_space("/");
				$ds=$login->convertfilesize($ds);
				echo "<tr><th>Total Disk Space</th><td>: ".$ds."</td></tr>";
				$df = disk_free_space("/");
				$df=$login->convertfilesize($df);
				echo "<tr><th>Free Disk Space</th><td>: ".$df."</td></tr>";
			?>
			</table>
		</div>
	</div>
<!--
	<div id="widget">
		<div id="strip">
			Courses & Enrollment
			<button type="button" class="primary">-</button>
		</div>
		<div id="widget-main">
			<table style="font:10px Arial;text-align:left;">
			<?php 
				$result=mysql_query("select * from course_info");
				$result1=mysql_query("select * from enrollment_info");
				echo "<tr><th>#Course(s):</th><td>".mysql_num_rows($result)."</td></tr>";
				echo "<tr><th>#Student(s):</th><td>".mysql_num_rows($result1)."</td></tr>";
				if(mysql_num_rows($result) != 0){
					while($row = mysql_fetch_array($result))
   					{
						echo "<tr><th>".$row['courseid']."</th><td>".$row['instructor']."</td></tr>";
					}
				}
				
			?>
			</table>
		</div>
	</div>
	<div id="widget">
		<div id="strip">
			Template(s) Information
			<button type="button" class="primary">-</button>
		</div>
		<div id="widget-main">
			<table style="font:10px Arial;text-align:left;">
			<?php 
				$result=mysql_query("select * from template_info");
				echo "<tr><th>#Template(s)</th><td>".mysql_num_rows($result)."</td></tr>";
				if(mysql_num_rows($result) != 0){
						$i=0;
						while($row = mysql_fetch_array($result))
   						{
							$i++;
							echo "<tr><th>OS ".$i."</th><td>".$row['os']."</td></tr>";
						}
				}
			?>
			</table>
		</div>
	</div>	-->
	<div id="widget">
		<div id="strip">
			Virtual Machines
			<button type="button" class="primary">-</button>
		</div>
		<div id="widget-main">
			<table style="font:serif;font-size:60%;text-align:left;">
			<?php 
				$info=libvirt_connect_get_information($libvirt->conn);
				echo "<tr><th>Total Number of VM(s)</th><td>: ".$info['num_total_domains']."</td></tr>";
				echo "<tr><th>Running VM(s)</th><td>: ".$info['num_active_domains']."</td></tr>";
				echo "<tr><th>Stopped VM(s)</th><td>: ".$info['num_inactive_domains']."</td></tr>";
			?>
			</table>
		</div>
	</div>
	</form>
</div>
</body>
</html>
