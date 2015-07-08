<?php
require "checklogin.php";
{
$user=$_SESSION['username'];
$pass=$_SESSION['password'];
?>
<html>
<title>Index</title>
<body bgcolor="LightGreen">
<div align="right">
	<center><font size=5 color="BLUE">vLab:Managing and Provisioning VMs for Labs</font></center>
	<a href="change.php"><b>Password Change</b></a>&nbsp;&nbsp;
	<a href="logout.php"><b>Logout</b></a>
</div>
<hr>
<?php
echo "welcome";
echo "<br/>";
echo $user;
}
?>
<script type="text/javascript">
var exec = require('child_process').exec;
var child;
child = exec(bash mac2Ip.sh,function (error, stdout, stderr) 
{
      console.log('stdout: ' + stdout);
      console.log('stderr: ' + stderr);
      if (error !== null) {
          console.log('exec error: ' + error);
      }
});
</script>
</body>
</html>
