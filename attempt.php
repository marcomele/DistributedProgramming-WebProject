<?php
	$user = sanitize($_POST['user']);
	$pswd = sanitize($_POST['passwd']);

	$pswd = md5(md5(md5($pswd)));

	$query = "SELECT * FROM users WHERE email = '$user' AND passwd = '$pswd'";
	$rs = mysqli_query($_SESSION['link'], $query);
	$nr = mysqli_num_rows($rs);
	$_SESSION['attempt'] = 1;
	if($nr > 0) {
		$_SESSION['authorized'] = true;
		$_SESSION['username'] = $user;
		$_SESSION['UID'] = mysqli_fetch_assoc($rs)['UID'];
		$_SESSION['thr'] = mysqli_fetch_assoc($rs)['threshold'];
	}
?>
