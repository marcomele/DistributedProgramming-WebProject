<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	/* HTTPS redirect */
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved permanently");
		header("Location: " . $redirect);
		exit();
	}
	$connect = mysqli_connect("localhost", "root", "root", "auction")
		or die("Error: database authentication failed");
	$_SESSION['link'] = $connect;
	function sanitize($var) {
 		$var = strip_tags($var);
 		$var = htmlentities($var);
 		$var = stripcslashes($var);
 		return mysqli_real_escape_string($_SESSION['link'], $var);
	}
?>
