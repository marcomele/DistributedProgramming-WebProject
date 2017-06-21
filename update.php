<?php
	include_once("connect.php");
	$uid = $_SESSION['UID'];
	$link = $_SESSION['link'];
	$query = "SELECT * FROM bid_state";
	$bidRS = mysqli_query($link, $query);
	$result = mysqli_fetch_assoc($bidRS);
	$bid = $result['value'];
	$bidder = $result['UID'];
	$newThr = sanitize($_POST['threshold']);
	$query = "SELECT threshold FROM users WHERE UID=" . $_SESSION['UID'];
	$rs = mysqli_query($_SESSION['link'], $query);
	$curThr = mysqli_fetch_assoc($rs)['threshold'];
	if($newThr <= $bid || ($curThr != NULL && $newThr <= $curThr)) {
		header("Location: secured.php?below");
		exit();
	}
	else {
		try {
			mysqli_autocommit($link, false);
			$query = "SELECT MAX(threshold) AS max FROM users";
			$prevMaxRS = mysqli_query($link, $query);
			if($prevMaxRS == FALSE)
				throw new Exception("unable to execute query ");
			$prevMax = mysqli_fetch_assoc($prevMaxRS)['max'];
			/* update user threshold */
			$query = "UPDATE users SET threshold = $newThr WHERE UID = $uid";
			if(!mysqli_query($link, $query))
				throw new Exception("unable to execute query ");
			/* set new bidder to the maximum threshold holder -- only if changed or first time */
			if($newThr > $prevMax || $prevMax === NULL) {
				$query = "UPDATE bid_state SET UID = (SELECT UID FROM users WHERE threshold = (SELECT MAX(threshold) FROM users))";
				if(!mysqli_query($link, $query))
					throw new Exception("unable to execute query ");
			}
			/* set new bid value to 0.01 + second maximum threshold -- if any */
			if($uid != $bidder) {
				if($prevMax == NULL)
				$value = $bid;
				else {
					if($newThr > $prevMax)
					$value = $prevMax + 0.01;
					else {
						if($newThr == $prevMax)
						$value = $newThr;
						else
						$value = $newThr + 0.01;
					}
				}
				$query = "UPDATE bid_state SET value = $value";
				if(!mysqli_query($link, $query))
				throw new Exception("unable to execute query ");
			}
			mysqli_commit($link);
		} catch (Exception $e) {
			mysqli_rollback($link);
			header("HTTP/1.1 500 Internal Server Error");
			header("Location: secured.php?error");
		}
	}
?>
