<?php
//For Database Connection
$LOGIN_DB_NAME = '';
$LOGIN_DB_UNAME = '';
$LOGIN_DB_PWD = '';
$LOGIN_DB_HOSTNAME = 'localhost';
$LOGIN_DB_PORT = '';
$LOGIN_DB_PREFIX = '';

//For libvirt Connection
$URI="qemu+tcp:///system";	//uri for qemu
$CREDENTIALS=Array(VIR_CRED_AUTHNAME=>"root",VIR_CRED_PASSPHRASE=>"xxxx"); // system credentials

//For LDAP Connection
$LDAP_SERVER="ldap.iitb.ac.in";
$Base_DN="dc=iitb,dc=ac,dc=in";
?>
