<?php
//	$redis = new Redis();
//	$redis->connect('127.0.0.1', 6379);
//	if(!$redis->ping())
//	{
//		echo "Redis is not running in the system";
//	}
	//echo "Stored string in redis:: ".$redis->get("tutorial-name");
	//print_r($redis->keys("*"));
	//$redis->del("tutorial-name");
	//print_r($redis->keys("*"));
	//$redis->set("cluster:num-nodes", "3");
/*	$num=$redis->get("cluster:num-nodes");
	for($i=0;$i<$num;$i++)
	{
		$ip=$redis->get("cluster:node".$i);	
		echo "IP Address: ".$ip."<br>";
		$cmd="virsh -c qemu+tcp://$ip/system ";
		$out=shell_exec($cmd."nodeinfo");
		$out=str_replace(' ', '', $out);
		$out=trim($out);
		$a=explode(PHP_EOL,$out);
		print_r($a);
		echo "<br>";

		$out=shell_exec($cmd."nodecpustats --percent");		
		$out=str_replace(' ', '', $out);
		$out=trim($out);
		$a=explode(PHP_EOL,$out);
		print_r($a);
		echo "<br>";

		$out=shell_exec($cmd."nodememstats");			
		$out=str_replace(' ', '', $out);
		$out=trim($out);
		$a=explode(PHP_EOL,$out);
		print_r($a);
		echo "<br><br>";
	}
	include "sched.class";
	$sched=new sched;
	$ip=$sched->info();
	echo $ip;*/
//	echo shell_exec("openssl passwd -1 ankit");  //This password can be set in /etc/shadow
/*	$g = guestfs_create ();
	if ($g == false) 
	{
		echo "Failed to create guestfs_php handle";
	}
	echo $g;
	$servers ="10.129.26.8:7000";
	guestfs_add_drive($g,"sheepdog://localhost:7000/133050024CS101");
	echo guestfs_launch($g);*/
	include "login.class";
	$login= new login;
	$cmd="select * from user_info into outfile '/tmp/info.csv';";
	$result=mysql_query($cmd);
//	while($row=mysql_fetch_array($result))
//		print_r($row);
	
?>


