<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
include "login.class";
$login=new login;
if(isset($_POST['submit']))
{
     if ($_POST['username']!="" && $_POST['password']!="")
     {
	if(isset($_POST['submit']) && ($_POST['level']=='admin'))
	{
		if(isset($_POST['submit'])&&(isset($_SESSION['destination'])))
		{
			$_SESSION['destination']="./index.php";			//Now its not remembering last accessed page
			$login->verify1($_POST['username'],$_POST['password'],$_POST['set'],$_POST['level']);
			exit(0);
		}
		 
		else if(isset($_POST['submit'])&&(!isset($_SESSION['destination'])))
		{
			$_SESSION['destination']="./index.php";   //after login where it will go .
			$login->verify1($_POST['username'],$_POST['password'],$_POST['set'],$_POST['level']);
			exit(0);
		} 
		else if (isset($_SESSION['username']))
		{
			header("Location: /vlab/index.php");
		}
		else if(isset($_COOKIE['user']))
		{
			$login->verify1($_COOKIE['user'],$_COOKIE['pwd'],NULL,$_COOKIE['level']);
			exit(0);
		}
	}
	else if(isset($_POST['submit']) && ($_POST['level']!='admin'))		//student or instructor
	{
		if(isset($_POST['submit'])&&(isset($_SESSION['destination'])))
		{
			$_SESSION['destination']="./index.php";			//Now its not remembering last accessed page
			$login->ldap_auth($_POST['username'],$_POST['password'],$_POST['set'],$_POST['level']);
			exit(0);
		}
		 
		else if(isset($_POST['submit'])&&(!isset($_SESSION['destination'])))
		{
			$_SESSION['destination']="./index.php";   //after login where it will go .
			$login->ldap_auth($_POST['username'],$_POST['password'],$_POST['set'],$_POST['level']);
			exit(0);
		} 
		else if (isset($_SESSION['username']))
		{
			header("Location: /vlab/index.php");
		}
		else if(isset($_COOKIE['user']))
		{
			$login->ldap_auth($_COOKIE['user'],$_COOKIE['pwd'],NULL,$_COOKIE['level']);
			exit(0);
		}	
	}
     }
     else
    {
	echo "<script>alert(\"Cannot give blank username or password !!!\")</script>"; 	
    }
}
?>

<html>
<head>
<title>
Login
</title>
<link rel="stylesheet" href="css/general.css" type="text/css" media="screen" /> 
</head>
<body style="background:white;">
<?php
$option=null;
if(isset($_GET['option']))
	$option=$_GET['option'];
if($option!=2)
{
	$option=1;
?>
<div id="upper">
	<font color="WhiteGloss"><b>vLab:Managing and Provisioning VMs for Labs</b></font>
</div> 
<br>
	<h1>vLAB Login</h1>
			<form name="form1" id="customForm" action="login.php" method="post">
	    <div>
			<!--<label for="username">Username</label>-->
			<input type="textbox" name="username" id="username" placeholder="Username"/>
	    </div>
	
	    <div>
			<!--<label for="password">Password</label>-->
			<input type="password" name="password" id="password" placeholder="Password"/>
            </div>
		<div>
			<select id="drop" name="level" style="width:222px";>
	  			<option value="student" selected="selected" style="text-align:center;">student</option>
	  			<option value="instructor" style="text-align:center;">instructor</option>
				<option value="admin" style="text-align:center;">administrator</option>
			</select>
		</div>
	   <div style="text-align:center;">
	   	Remember Me<br><input type="checkbox" name="set" value="rem_me"> 	
	   </div>         
	   <div>     
			<input type="submit" id="submit" name="submit" value="Sign in"/>
			<!--<input type="reset" id="reset" name="reset" value="reset"/>-->
	    </div>

	    <div>
			<a href="login.php?option=2">Forgot Password</a>
	    </div>
	    <div><a href="../vlab/documents/user_guide.pdf" target="_blank">User Guide</a></div>
<!--	     <div><a href="sheepdog.php">About Sheepdog Cluster</a></div>
	    <div>
			<a href="register.php">Register</a>
	    </div>
-->
</form>
<?php
}
?>
<?php
if($option==2)
{
?>
<div id="upper">
		<font color="WhiteGloss"><b>vLab:Managing and Provisioning VMs for Labs</b></font>
	</div>
<div id="container"> 
			<h1>Forgot Password</h1> 
			<form name="form2" id="customForm" action="forgot.php" method="post">
			
			<div>
			<label for="email">Enter Your Email Id</label>
			<input type="textbox" placeholder="Only for admin" name="email" id="email"/>
			</div>
			
			<div>     
			<input type="submit" id="submit" name="submit" value="submit"/>
			</div>
			</form>
</div>
<?php
}
?>

</body>
</html>
