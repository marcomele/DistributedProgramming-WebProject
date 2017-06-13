<?php
	$cond = isset($_GET['expired']);
	session_start();
	mysqli_close($_SESSION['link']);
	$cookie_params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 3600*24,
		$cookie_params['path'], $cookie_params['domain'],
		$cookie_params['secure'], $cookie_params['httponly']);
	session_destroy();
	if($cond) {
		header("HTTP/1.1 307 Temporary redirect");
		header('Location: index.php?expired');
	}
	header("HTTP/1.1 307 Temporary redirect");
	header('Location: index.php');
?>
