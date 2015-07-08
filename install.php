<?php
if(!isset($_SESSION)) 
{ 
        session_start(); 
} 
?>
<html>
<head>
<title>
Installation
</title>
<link rel="stylesheet" href="css/general.css" type="text/css" media="screen" /> 
<script type="text/javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery_validation.js"></script> 
<script type="text/javascript" src="js/validation.js"></script>
<script type="text/javascript">
function toggle(id)
{
	if(document.getElementById(id).style.display=="block")
		document.getElementById(id).style.display="none";
	else
		document.getElementById(id).style.display="block";
}
</script>
</head>
<body>

<div id="container"> 
<?php
if($_POST['submit']=="Save and Install")
{
		$permission=substr(sprintf('%o', fileperms('./data/settings.php')), -4); 
		if(($permission=="0666")||($permission=="0777"))
		{
			$data='<?php'."\n".'$LOGIN_DB_NAME'." = '$_POST[dbname]';\n".'$LOGIN_DB_UNAME'." = '$_POST[dbuname]';\n".'$LOGIN_DB_PWD'." = '$_POST[dbpwd]';\n".'$LOGIN_DB_HOSTNAME'." = '$_POST[hostname]';\n".'$LOGIN_DB_PORT'." = '$_POST[port]';\n".'$LOGIN_DB_PREFIX'." = '$_POST[tb_prefix]';\n?>";
			file_put_contents("./data/settings.php",$data);
			echo '<div id="customForm"><div><span class="error">';
			$con=mysql_connect($_POST['hostname'],$_POST['dbuname'],$_POST['dbpwd']);
			echo '</span></div>';
			if ($con)
			{
				if(mysql_select_db($_POST['dbname'], $con))
				{
					$sql = "CREATE TABLE IF NOT EXISTS `user_info` (
							`username` varchar(32) NOT NULL DEFAULT '',
							`name` varchar(32) NOT NULL DEFAULT '',
							`password` varchar(32) DEFAULT NULL,
							`email` varchar(40) NOT NULL,
							`level` ENUM('admin','instructor', 'student'),
							PRIMARY KEY (`Username`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
					mysql_query($sql,$con);

					$sql1= "CREATE TABLE IF NOT EXISTS `course_info` (
						`instructor` varchar(32) NOT NULL,
						`courseid` varchar(32) NOT NULL,
						`template` varchar(32),
						PRIMARY KEY (`courseid`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
					mysql_query($sql1,$con);
					
					$sql2= "CREATE TABLE IF NOT EXISTS `enrollment_info` (
						`rollno` varchar(32) NOT NULL,
						`courseid` varchar(32) NOT NULL,
						PRIMARY KEY(`rollno`,`courseid`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
					mysql_query($sql2,$con);

					$sql3= "INSERT INTO user_info VALUES ('admin','admin','password','admin@example','admin');";
					mysql_query($sql3,$con);
				
					$sql4= "CREATE TABLE IF NOT EXISTS `vm_info` (
						`vmname` varchar(32) NOT NULL,
						`mac`	varchar(20) NOT NULL,
						`courseid` varchar(32) NOT NULL,
						`status` tinyint(1) unsigned NOT NULL DEFAULT '0',
						PRIMARY KEY(`vmname`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
					mysql_query($sql4,$con);
					
					$sql5= "CREATE TABLE IF NOT EXISTS `template_info` (
						`templatename` varchar(32) NOT NULL,
						`os`	varchar(20) NOT NULL,
						`username` varchar(32) NOT NULL,
						`password` varchar(32) NOT NULL,
						PRIMARY KEY(`templatename`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
					mysql_query($sql5,$con);
		//CREATE TABLE IF NOT EXISTS template_info (templatename varchar(32) NOT NULL,os varchar(20) NOT NULL,username varchar(32) NOT NULL,password varchar(32) NOT NULL,PRIMARY KEY(templatename));
?>
			<div>
			<span class="success"><strong>Congratulations!</strong> Login script has been installed successfully.</span>
			</div>
			<div>
			<span class="success">Also mysql database connection also has been tested successfully.</span>
			</div>
			</div>
<?php
				}
				else
				{
				echo '
					<div>
					<span class="error">'.mysql_error().'</span>
					</div>
					<div>
					<span class="error">Either the database name is incorrect or this user does not have proper permissions.</span>
					</div>
					<div align="center">
					<span><a href="install.php"><strong>Click here to go back</strong></a></span>
					</div>
					</div>';
				}
				mysql_close($con);
			
			}
			else
			{
					echo '
					<div>
					<span class="error">'.mysql_error().'</span>
					</div>
					<div>
					<span class="error">The login details provided by you are incorrect. Please re-enter.</span>
					</div>
					
					<div align="center">
					<span><a href="install.php"><strong>Click here to go back</strong></a></span>
					</div>
					</div>';
			exit(0);
			}
?>
		
		
		

<?php
		}
		else
		{
?>
		<div>
		<span class="error"><strong>Change</strong> file permissions so that it is writable by the web server.</span><br /><br />
		<span class="error">If you are unsure how to grant file permissions, please consult the <a>handbook</a>.</span><br /><br />
		<span class="error">More details about installing this login script are available in INSTALL.txt</span><br /><br />
		</div>
		<div align="center">
		<span><a href="install.php"><strong>Click here to check again</strong></a></span>
		</div>
<?php
		}
		

}
else
{
	

?>
	<h1>Installation</h1> 
	<form name="form2" id="customForm" action="install.php" method="post">
<?php
	if(!file_exists("./data/settings.php"))
	{
?>
		<div>
		<span class="error">The installer requires that you create a settings file as part of the installation process.</span><br /><br />
		<span class="error">Copy the <strong>default.settings.php</strong> file to <strong>settings.php</strong>.</span><br /><br />
		<span class="error"><strong>Change</strong> file permissions so that it is writable by the web server.</span><br /><br />
		<span class="error">If you are unsure how to grant file permissions, please consult the <a>handbook</a>.</span><br /><br />
		<span class="error">More details about installing this login script are available in INSTALL.txt</span><br /><br />
		</div>
		<div align="center">
		<span><a href="install.php"><strong>Click here to check again</strong></a></span>
		</div>
	
<?php		
	}
	else
	{
?>
		<div>
		<h1>Basic Setup</h1> 
		</div>
		<div>
			<label for="dbname">Database type</label>
			<input type="radio" id="dbtype" name="dbtype" class="radio" value="mysql" checked disabled/>
			<span id="dbtypeInfo"><strong>mysql</strong></span>
		</div>
		<div>
			<label for="dbname">Database name</label>
			<input type="text" id="dbname" name="dbname" />
			<span id="nameInfo">Enter your database name</span>
		</div>
		<div>
			<label for="dbuname">Database username</label>
			<input type="text" id="dbuname" name="dbuname" />
			<span id="nameInfo">Enter the username </span>
		</div>
		<div>
			<label for="password">Database password</label>
			<input type="password" name="dbpwd" id="dbpwd" />   
			</span><span id="result"></span>
		</div>
		<div>
		<h1 onclick="toggle('advanced');">Advanced Configuration</h1> 
		</div>
		<div id="advanced" style="{display: none;}">
		<div>
			<label for="hostname">Hostname</label>
			<input type="text" id="hostname" name="hostname" value="localhost"/>
			<span id="emailInfo"></span>
		</div>
		
		<div>
			<label for="port">Port</label>
			<input type="text" id="port" name="port" />
			<span id="emailInfo"></span>
		</div>
		
		<div>
			<label for="tb_prefix">Table Prefix</label>
			<input type="text" id="tb_prefix" name="tb_prefix" />
			<span id="emailInfo"></span>
		</div>
		</div>
		<div>
			<input id="submit" type="submit" name="submit" value="Save and Install" />
		</div>
		
	</form>
<?php
}
}
?>
	</div>
</body>
</html>
