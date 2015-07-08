<center>
<form name="form2" action="ldaplogin.php" method="post" >
	<input type="text" name="username" placeholder="Username" /><br>
	<input type="password" name="password" placeholder="password"/><br>
	<input type="submit" name="submit" value="Log in"/>
</form>
</center>
<?php
if(isset($_POST['submit']))
{
	$user=$_POST['username'];
	$pass=$_POST['password'];
	ldap_auth($user,$pass);
}
	function ldap_auth($ldap_id, $ldap_password)
	{
		$ds = ldap_connect("ldap.iitb.ac.in") 
		or die("Unable to connect to LDAP server. Please try again later.");
		if($ldap_id=='') die("You have not entered any LDAP ID. Please go back and fill it up.");
		if($ldap_password=='') die("You have not entered any password. Please go back and fill it up.");
		$sr = ldap_search($ds,"dc=iitb,dc=ac,dc=in","(uid=$ldap_id)");
		$info = ldap_get_entries($ds, $sr);
		$roll = $info[0]["employeenumber"][0];
		$ldap_id = $info[0]['dn'];
		if(@ldap_bind($ds,$ldap_id,$ldap_password))
		{
			echo '<pre>'; print_r($info[0]); echo '</pre>';			//for every information
			//echo "<script>location.replace(\"student.php\")</script><br>";	//where to redirect
			//echo $roll."|".$ldap_id;

			$level=$info[0]['employeetype'][0];
			$name =$info[0]['gecos'][0]; 
			$email=$info[0]['mail'][0];			

			echo $level."<br>";
			echo $name."<br>";
			echo $email;
		}
		else
		{
			echo  "<script>alert(\"Wrong Username or Password.\")</script><br>";
		}
	}
?>

