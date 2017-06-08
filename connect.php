<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$connect = mysqli_connect("localhost", "root", "root", "auction")
		or die("Error: database authentication failed");
	$_SESSION['link'] = $connect;
?>
