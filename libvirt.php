<?php
	include "libvirt.class";
	$libvirt=new libvirt;
	$vmname="133050024CS101";
	echo $vmname;
	$ip="10.129.12.26";
	$conn=$libvirt->connect_libvirt_remote($ip);
	echo libvirt_connect_get_hostname($conn);

//	$output=$libvirt->domain_shutdown($vmname);
//	if(!$output) echo libvirt_get_last_error();
//	else echo "<pre>$output</pre>";
//	echo $libvirt->vncport($vmname);
?>


