<html>
<head>
<title>
Sheepdog Information
</title>
</head>
<body style="background:white;text-align:left;font-size:12 px;font-style: normal;font-weight: normal;margin:0pt;">
<div id="upper" style="text-align:center;width: 100%; padding:2%;
	border:0px solid #191970; float:top;position:relative;
	background: rgb(44,83,158); 
	background: -moz-linear-gradient(left, rgba(44,83,158,1) 0%, rgba(44,83,158,1) 100%); 
	background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(44,83,158,1)), color-stop(100%,rgba(44,83,158,1))); 
	background: -webkit-linear-gradient(left, rgba(44,83,158,1) 0%,rgba(44,83,158,1) 100%); 
	background: -o-linear-gradient(left, rgba(44,83,158,1) 0%,rgba(44,83,158,1) 100%); 
	background: -ms-linear-gradient(left, rgba(44,83,158,1) 0%,rgba(44,83,158,1) 100%); 
	background: linear-gradient(to right, rgba(44,83,158,1) 0%,rgba(44,83,158,1) 100%); 
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2c539e', endColorstr='#2c539e',GradientType=1 );"">
	<font color="WhiteGloss"><b>Information About Sheepdog Cluster</b></font>
</div>
<html>
<body>
<a href="login.php">Go back to vLab Login </a><br>
<a href="sheepdog.php"> Node Information </a><br>
<a href="sheepdog.php?option=2"> VDI Information </a><br>
<a href="sheepdog.php?option=3"> Cluster Infomation</a><br>
<a href="sheepdog.php?option=4"> Add disk to sheepdog cluster(vdi,snapshot or incremental disk)</a><br>
<a href="sheepdog.php?option=5"> Start sheep on each node</a><br>
<a href="sheepdog.php?option=6"> Configure Sheepdog,zookeeper</a><br>
<a href="sheepdog.php?option=7"> Cluster with local driver</a><br><br>
<?php
	echo "<hr>";
	$option=null;
	if(isset($_GET['option'])){
		$option=$_GET['option'];
	}
	else{
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
Baseimage+overlay in sheepdog:
=============================
1. Create a base image
      $ qemu-img convert -f qcow2 wheezy.qcow2 sheepdog:wheezy

2. Take a snapshot to use as a base image
      $ qemu-img snapshot -c CS101 sheepdog:UbuntuServer

3. Create a cloned image from sheepdog:debian:base
      $ qemu-img create -b sheepdog:UbuntuServer:CS101 sheepdog:debian_inc

4. Delete a snapshot
	$ dog vdi delete -s CS101 ServerTemplate";
	echo "<pre>$out</pre>";
	}
	if($option==5)
	{
		echo "sudo sheep -n /mnt/sheep/0 -c zookeeper:10.129.26.8:2181,10.129.28.250:2181,10.129.26.104:2181,10.129.26.242:2181";
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

service zookeeper restart

Sheepdog Configuration:
======================
/dev/sda2 /mnt/sheep/0 ext4 defaults,user_xattr,noatime 0 0
sudo sheep /mnt/sheep/0 -c zookeeper:10.129.26.8:2181,10.129.28.250:2181,10.129.26.104:2181,10.129.26.242:2181	
sudo sheep -n /mnt/sheep/0 -c zookeeper:10.129.26.8:2181,10.129.28.250:2181,10.129.26.104:2181, 	10.129.26.242:2181	


Baseimage+overlay in sheepdog:
=============================
1. Create a base image
	$ qemu-img convert -f qcow2 wheezy.qcow2 sheepdog:wheezy

2. Take a snapshot to use as a base image
	$ qemu-img snapshot -c CS101 sheepdog:UbuntuServer

3. Create a cloned image from sheepdog:debian:base
	$ qemu-img create -b sheepdog:UbuntuServer:CS101 sheepdog:debian_inc";
			echo "<pre>$out</pre>";
	}
	if($option==7){
	$out="
# Create a 6 node cluster with local driver
 $ for i in `seq 0 5`; do sheep /tmp/store$i -n -c local -z $i -p 700$i;done
 $ dog cluster format

# Create a replicated thin-provisioned 20G volume with 3 copies
 $ dog vdi create -c 3 replica 20G
 $ dog vdi list # show vdi list
 $ dog node info # show node information
 $ dog cluster info # show cluster infomation

# Create a erasure-coded (4 data strips and 2 parity strips) 20G volume
 $ dog vdi create -c 4:2 erasure 20G

# Now you should have 2 vdi created
 $ dog vdi list

# You can install OS on these volumes with upstream QEMU
 $ qemu-system-x86_64 -m 1024 --enable-kvm \
   -drive file=sheepdog:erasure,if=virtio -cdrom path_to_your_iso

# or attach the volumes with existant VM
 $ qemu-system-x86_64 -m 1024 --enable-kvm \
   -drive file=your_image,if=virtio -drive file=sheepdog:erasure,if=virtio

# Take a live disk-only snapshot of running VM
 $ dog vdi snapshot -s tag erasure

# Mount the volume(vdi) to local storage file system
 $ sheepfs dir
 $ echo erasure > dir/vdi/mount
# then you can do whatever with the mounted file at dir/vdi/volume/erasure";
	echo "<pre>$out</pre>";
	
	}
?>
</body>
</html>


