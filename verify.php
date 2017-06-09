	<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	session_start();
	include("expire.php");
	include_once("connect.php");
	if(!isset($_SESSION['authorized'])) {
		echo("<script type='text/javascript'>alert('User not authenticated or session expired.')</script>");
		header("location:index.php");
	}
?>
