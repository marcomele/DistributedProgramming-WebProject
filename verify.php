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
	} else {
		session_start();
		include("expire.php");
		include_once("connect.php");
		if(!isset($_SESSION['authorized'])) {
			header("HTTP/1.1 401 Unauthorized");
			header("Location: index.php");
		}
	}
?>
