<?php
$mysqli = new mysqli($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
if ($mysqli->connect_errno){
	die('Connect Error:'.$mysqli->connect_error);
}
$mysqli->set_charset('UTF8');
?>