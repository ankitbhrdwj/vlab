<?php
$username = "root";
$password = "";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");
echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("vlab",$dbhandle)
  or die("Could not select examples");

//execute the SQL query and return records
$result = mysql_query("SELECT * FROM users");

//fetch tha data from the database
while ($row = mysql_fetch_array($result)) {
   echo "RollNO : ".$row{'rollno'}." Name : ".$row{'name'}." Password : ".$row{'password'}."<br>";
}
//close the connection
mysql_close($dbhandle);
?>	
