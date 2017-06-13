<?php
	if(!isset($_SESSION['expired'])) {
		$_SESSION['expired'] = time();
	} else {
		if($_SESSION['expired'] + 120 < time()) {
			header("HTTP/1.1 307 Temporary redirect");
			header("Location: logout.php?expired");
		} else
			$_SESSION['expired'] = time();
	}
?>
