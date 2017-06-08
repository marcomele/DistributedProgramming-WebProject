<?php
	include_once("connect.php");
	$uid = $_SESSION['UID'];
	$link = $_SESSION['link'];
	$query = "SELECT * FROM bid_state";
	$bidRS = mysqli_query($link, $query);
	$result = mysqli_fetch_assoc($bidRS);
	$bid = $result['value'];
	$bidder = $result['UID'];
	$newThr = $_SESSION['thr'];
	if($newThr < $bid)
		echo("<script type='text/javascript'>alert('You cannot set a threshold which is not greater than the current bid.')</script>");
	else {
		mysqli_begin_transaction($link);
		$query = "SELECT MAX(threshold) FROM users";
		mysqli_query($link, $query);
		$prevMaxRS = mysqli_query($link, $query);
		$prevMax = mysqli_fetch_assoc($prevMaxRS);
		/* update user threshold */
		$query = "UPDATE users SET threshold = $newThr WHERE UID = $uid";
		mysqli_query($link, $query);
		$plus = 0.00;
		/* set new bidder to the maximum threshold holder -- only if changed */
		if($newThr > $prevMax) {
			$plus = 0.01;
			$query = "UPDATE bid_state SET UID = (SELECT UID FROM users WHERE threshold = (SELECT MAX(threshold) FROM users))";
			mysqli_query($link, $query);
		}
		/* set new bid value to 0.01 + second maximum threshold -- if any */
		$query = "UPDATE bid_state SET value = (SELECT (CASE MAX(threshold) WHEN NULL THEN NULL ELSE MAX(threshold) + $plus END) FROM users WHERE UID != $bidder)";
		mysqli_query($link, $query);
		mysqli_commit($link);
	}
?>
