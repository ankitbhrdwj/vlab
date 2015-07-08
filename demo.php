<?php
	$res = webdav_connect('http://ankit-desktop.local/svn/', '', '');
	webdav_put('/templateCS101.qcow2', file_get_contents('/home/ankit/Desktop/backend1/template/templatecs101.qcow2'), $res);
	$a = webdav_get('/templateCS101.qcow2', $res);
	echo $a;
	webdav_unlink('/templateCS101.qcow2', $res);
	webdav_rename('/templateCS101.qcow2', '/templatecs101.qcow2', TRUE, $res);
	webdav_copy('/templatecs101.qcow2', '/template101.qcow2', TRUE, TRUE, $res);
	webdav_unlink('/template101.qcow2', $res);
	webdav_close($res);
?>
