<?php
// All code below obtained from "Connecting to a MySQL Database in PHP" section in PHP and MySQL class wiki
// Content of database.php
$mysqli = new mysqli('localhost', 'wustl_inst', 'wustl_pass', 'calendar');
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>