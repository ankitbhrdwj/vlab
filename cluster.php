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

$redis=new Redis();
$redis->connect('127.0.0.1',6379);
?>
<html>
<head>
<title>Sheepdog Cluster Information</title>
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
		<li><div id="button"><a href="admin.php">Home</a></div></li>
		<li><div id="button"><a href="addtemplate.php">Add Template</a></div></li>
		<li><div id="button"><a href="update.php"> Update Information</a></div></li>
		<li><div id="button"><a href="register.php" >Add-Admin</a></div></li>
		<li><div id="button"><a href="cluster.php" class="active">Sheepdog Cluster</a></div></li>
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
<div id="main" style="background:transparent;text-align:left;">
	<div id="topmenu">
		<div id="topbutton" style="float:left;"><a href="cluster.php">Add Nodes to Cluster</a></div>
		<div id="topbutton" style="float:left;"><a href="cluster.php?option=2">Cluster Information</a></div>
		<div id="topbutton" style="float:left;"><a href="cluster.php?option=3">Cluster Status</a></div>
	</div>
</div>
<?php
	$option=null;
	if(isset($_GET['option']))
		$option=$_GET['option'];
	if($option<=1 || !$option)
	{
		$option=1;	
	}
if($option==1)
{
	if(isset($_POST['submit']) && ($_POST['submit']=="Add Nodes to Cluster"))
	{
		if($_POST['number']!="" && $_POST['ip']!="" && is_numeric($_POST['number']))
		{
			if($redis->ping())
				$redis->set("cluster:num-nodes",$_POST['number']);
			else 
				echo "Error connecting to Redis !!!";
			$ip=explode(",",$_POST['ip']);
			for($i=0;$i<$_POST['number'];$i++)
			{
				$ipadd=$ip[$i];
				if(filter_var($ipadd,FILTER_VALIDATE_IP)){
					if($redis->ping()){
						$key="cluster:node".$i;
						$res=$redis->set($key,$ipadd);
						if(!$res)
							$redis->del("cluster:num-nodes");
					}
				}
			}
			$redis->bgSave();
			echo "<script>alert(\"Nodes added to cluster !!!\")</script>";
		}
		else
		{
			echo "<script>alert(\"Give proper values !!!\")</script>";
		}
	}
	echo "<div id=\"topmain\" style=\"text-align:center;\">";
	echo "*You can change the configuration at any time. Only the nodes given here, will be used to run VM.";
	echo "<form id=\"myform\" action=\"cluster.php?option=1\" method=\"post\">";
	echo "<table align=center>";
	echo "<tr><td>Number of Nodes:</td><td><input type=\"text\" name=\"number\"></td></tr><br>";
	echo "<tr><td>IP of Nodes:</td><td><input type=\"text\" name=\"ip\" placeholder=\"IP-Address1,IP-Address2\"></td></tr><br>"; 
	echo "</table>";
	echo "<br>";
	echo "<input type=\"Submit\" value=\"Add Nodes to Cluster\" name=\"submit\">";
	echo "</form>";
	echo "<hr>";
	
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	if($redis->ping())
	{
		$num=$redis->get("cluster:num-nodes");
		$res=null;
		for($i=0;$i<$num;$i++)
		{
			$ip=$redis->get("cluster:node".$i);
			echo "<div id=\"widget\" style=\"float:left;font-size:100%;width:30%;margin-left:2.2%;\">";
			echo "<div id=\"strip\">";
				echo "NODE :".$ip;
				echo "<button type=\"button\" class=\"primary\">-</button>";
			echo "</div>";
			echo "<div id=\"widget-main\">";
				echo "<table style=\"font:serif;font-size:100%;text-align:left;\">"; 
					$uri="qemu+tcp://".$ip."/system";
					$con=libvirt_connect($uri,false);
					if(!$con)
						echo libvirt_get_last_error();
					$info=libvirt_connect_get_information($con);
					echo "<tr><th>Hostname</th><td>: ".$info['hostname']."</td></tr>";
					echo "<tr><th>Hypervisor</th><td>: ".$info['hypervisor_string']."</td></tr>";
					echo "<tr><th>Max VCPU(s) per VM</th><td>: ".$info['hypervisor_maxvcpus']."</td></tr>";
					$info1=libvirt_node_get_info($con);
					echo "<tr><th>CPU(s)</th><td>: ".$info1['cpus']."</td></tr>";
					echo "<tr><th>CPU frequency</th><td>: ".$info1['mhz']."</td></tr>";
					echo "<tr><th>Architecture</th><td>: ".$info1['model']."</td></tr>";
					echo "<tr><th>Memory</th><td>: ".$info1['memory']."</td></tr>";

					echo "<tr><th>Total Number of VM(s)</th><td>: ".$info['num_total_domains']."</td></tr>";
					echo "<tr><th>Running VM(s)</th><td>: ".$info['num_active_domains']."</td></tr>";
					echo "<tr><th>Stopped VM(s)</th><td>: ".$info['num_inactive_domains']."</td></tr>";	
				echo "</table>";
			echo "</div>";
			echo "</div>";
		}
	}	
	echo "</div>";
	$redis->close();
}

if($option==2) //Cluster Information
{	
	echo "<div id=\"topmain\">";
	echo "<table align=center border=1>";
	$a=$redis->get("cluster:num-nodes");
	echo "<tr><td>Number of Nodes in the cluster</td><td>$a</td></tr>";
	for($i=0;$i<$a;$i++)
	{
		$ip=$redis->get("cluster:node".$i);
		echo "<tr><td>IP address of Node $i in the cluster</td><td>$ip</td></tr>";
	}
	echo "</table>";	
	echo "</div>";
}

if($option==3)	//Cluster Status
{
?>
<div id="topmain">
<div style="text-decoration: Underline !important;color: Green;font-size:12px;">
<a href="cluster.php?option=3">Cluster Node Information </a><br>
<a href="cluster.php?option=3?part=2"> Disk Image Information </a><br>
<a href="cluster.php?option=3?part=3"> Cluster Infomation</a><br>
<a href="cluster.php?option=3?part=4"> Add disk to sheepdog cluster(vdi,snapshot or incremental disk)</a><br>
<a href="cluster.php?option=3?part=5"> Start sheep on each node</a><br>
<a href="cluster.php?option=3?part=6"> Configure Sheepdog,zookeeper</a><br>
<a href="cluster.php?option=3?part=7"> Cluster with local driver & Commands</a><br><br>
</div>
<?php
	echo "<hr>";
	$option=null;
	if(isset($_GET['option'])){
		$option=$_GET['option'];
		$a=explode("=",$option);
		$option=$a[1];
		if(!$option)
			$option=1;
	}
	if($option==1)
	{
		$cmd="dog node info 2>&1";
		$out=shell_exec($cmd);
		echo "<pre>".$out."</pre>";
	}
	if($option==2)
	{
		$cmd="dog vdi list 2>&1";
		$out=shell_exec($cmd);
		echo "<pre>".$out."</pre>";	
	}
	if($option==3)
	{
		$cmd="dog cluster info 2>&1";
		$out=shell_exec($cmd);
		echo "<pre>".$out."</pre>";
	}
	if($option==4)
	{
	$out="	
1. Create a base image
      $ qemu-img convert -f qcow2 diskimage.qcow2 sheepdog:diskimage.qcow2 

2. Take a snapshot to use as a base image
      $ qemu-img snapshot -c CS101 sheepdog:diskimage.qcow2

3. Create a cloned image from sheepdog:debian:base
      $ qemu-img create -b sheepdog:diskimage.qcow2:CS101 sheepdog:133050024CS101

4. Delete a disk image
	$ dog vdi delete diskimage.qcow2

5. Delete a snapshot
	$ dog vdi delete -s CS101 ServerTemplate";
	echo "<pre>$out</pre>";
	}
	if($option==5)
	{
		echo "sudo sheep -n /mnt/sheep/0 -c zookeeper:IP Address1:2181,IP Address2:2181,IP Address2:2181,..";
	}
	if($option==6)
	{
		$out="
Zookeeper configuration:
=======================
nano /etc/zookeeper/conf/zoo.cfg
server.1=10.129.26.8:2888:3888
server.2=10.129.28.250:2888:3888
server.3=10.129.26.104:2888:3888

Seeting IDs in Zookeeper:
========================
# Give each node a unique id
# On 10.129.26.8
nano /etc/zookeeper/conf/myid
1
# On 10.129.28.250
nano /etc/zookeeper/conf/myid
2
# On 10.129.26.104
nano /etc/zookeeper/conf/myid
3

$ sudo service zookeeper restart

Mounting for Sheepdog Cluster:
=============================
$ nano /etc/fstab
/dev/sda2 /mnt/sheep/0 ext4 defaults,user_xattr,noatime 0 0";
			echo "<pre>$out</pre>";
	}
	if($option==7){
	$out="
$ sheep /mnt/sheep/0 -n -c local -z 0 -p 7000 #Start the cluster on local node

$ dog cluster format -c 3 #Format the cluster with 3 copies of each object

$ dog vdi create -c 3 replica 20G # Create a replicated thin-provisioned 20G volume with 3 copies

$ dog vdi list # show vdi list

$ dog node info # show node information

$ dog cluster info # show cluster infomation

$ dog vdi create -c 4:2 erasure 20G # Create a erasure-coded (4 data strips and 2 parity strips) 20G volume

$ qemu-system-x86_64 -m 1024 --enable-kvm  -drive file=sheepdog:erasure,if=virtio -cdrom path_to_your_iso
# You can install OS on these volumes with upstream QEMU

$ qemu-system-x86_64 -m 1024 --enable-kvm -drive file=your_image,if=virtio -drive file=sheepdog:erasure,if=virtio
# or attach the volumes with existant VM";
	echo "<pre>$out</pre>";
}
?>
</div>
<?php } ?>
</body>
</html>
