<?php
	session_start();
	mysqli_close($_SESSION['link']);
	session_destroy();
	header("HTTP/1.1 307 Temporary redirect");
	header('Location: index.php');
	die();
?>
