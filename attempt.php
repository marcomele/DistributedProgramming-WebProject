<?php
	$user = mysqli_real_escape_string($connect, $_POST['user']);
	$pswd = mysqli_real_escape_string($connect, $_POST['passwd']);

	$pswd = md5(md5(md5($pswd)));

	$query = "SELECT * FROM users WHERE email = '$user' AND passwd = '$pswd'";
	$rs = mysqli_query($connect, $query);
	$nr = mysqli_num_rows($rs);
	if($nr > 0) {
		$_SESSION['authorized'] = true;
		$_SESSION['username'] = $user;
		$_SESSION['UID'] = mysqli_fetch_assoc($rs)['UID'];
		$_SESSION['thr'] = mysqli_fetch_assoc($rs)['threshold'];
		header("location: secured.php");
	} else {
		echo("<script type='text/javascript'>alert('No match found: wrong username or password.')</script>");
	}
?>
