<?php
	session_start();
	mysqli_close($_SESSIO['link']);
	session_destroy();
	header('location:index.php');
	die();
?>
