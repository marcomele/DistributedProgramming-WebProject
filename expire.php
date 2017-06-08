<?php
	if(!isset($_SESSION['expired'])) {
		$_SESSION['expired'] = time();
	} else {
		if($_SESSION['expired'] + 120 < time()) {
			session_destroy();
			echo("<script type='text/javascript'>alert('Your session has expired, please log in again.')</script>");
			header("location:index.php");
		} else
			$_SESSION['expired'] = time();
	}
?>
