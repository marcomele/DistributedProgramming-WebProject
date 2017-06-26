<?php
/********************************************************
 * Library file for Auction Web Portal
 * Created by Marco Mele in June 2017
 *
 * PHP version: 7.0.18
 *
 ********************************************************/

function httpsRedirect() {
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved permanently");
		header("Location: " . $redirect);
		exit();
	}
}

function logout($isExpired) {
	$cookie_params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 3600 * 24,
		$cookie_params['path'], $cookie_params['domain'],
		$cookie_params['secure'], $cookie_params['httponly']);
	session_destroy();
	header("HTTP/1.1 307 Temporary redirect");
	if($isExpired == true) {
		header('Location: index.php?expired');
		exit;
	}
	header('Location: index.php');
	exit;
}

function checkSessionExpiral() {
	if(!isset($_SESSION['expired'])) {
		$_SESSION['expired'] = time();
	} else {
		if($_SESSION['expired'] + 120 < time()) {
			logout(true);
		} else
			$_SESSION['expired'] = time();
	}
}

function connect() {
	httpsRedirect();
	$link = mysqli_connect("localhost", "s241882", "nhosenie", "s241882")
		or die("500 Server Error: this server is experiencing troubles in the database interaction.");
	$_SESSION['link'] = $link;
	return $link;
}

function sanitize($var, $link) {
	$var = strip_tags($var);
	$var = htmlentities($var);
	$var = stripcslashes($var);
	return mysqli_real_escape_string($link, $var);
}

function authenticate($user, $pswd, $link) {
	$user = sanitize($user, $link);
	$pswd = sanitize($pswd, $link);
	$pswd = md5(md5(md5($pswd)));

	$_SESSION['attempt'] = 1;
	try {
		$query = "SELECT * FROM users WHERE email = '$user' AND passwd = '$pswd'";
		$rs = mysqli_query($link, $query);
		if($rs == false)
			throw new Exception("Error Processing Request");
	} catch (Exception $e) {
		header("Location: index.php?failure");
		exit;
	}
	$nr = mysqli_num_rows($rs);
	if($nr > 0) {
		$_SESSION['authorized'] = true;
		$_SESSION['username'] = $user;
		$_SESSION['UID'] = mysqli_fetch_assoc($rs)['UID'];
		$_SESSION['thr'] = mysqli_fetch_assoc($rs)['threshold'];
		checkSessionExpiral();
	}
}

function showPhpErrors() {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

function restrict() {
	if(!isset($_SESSION['authorized'])) {
		header("HTTP/1.1 401 Unauthorized");
		header("Location: index.php?unauthorized");
	}
}

function update($link, $threshold, $uid) {
	try {
		$query = "SELECT * FROM bid_state";
		$bidRS = mysqli_query($link, $query);
		if($bidRS == false)
			throw new Exception("Error Processing Request");
		$result = mysqli_fetch_assoc($bidRS);
		$bid = $result['value'];
		$bidder = $result['UID'];
		$newThr = sanitize($threshold, $link);
		$query = "SELECT threshold FROM users WHERE UID=" . $_SESSION['UID'];
		$rs = mysqli_query($link, $query);
		if($rs == false)
			throw new Exception("Error Processing Request");
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
						throw new Exception("Error Processing Request");
				}
				mysqli_commit($link);
				} catch (Exception $e) {
					mysqli_rollback($link);
					throw new Exception("Error Processing Request");
				}
			}
	} catch(Exception $e) {
		header("HTTP/1.1 500 Internal Server Error");
		header("Location: secured.php?error");
	}
}

function registerUser($user, $pswd, $pswd2, $link) {
	$user = sanitize($user, $link);
	$pswd = sanitize($pswd, $link);
	$pswd2 = sanitize($pswd2, $link);
	if(!filter_var($user, FILTER_VALIDATE_EMAIL)) {
		header("Location: signup.php?email");
		exit();
	}
	if(!preg_match("/^.*(?=.*[0-9])(?=.*[a-z]).*$/", $pswd)) {
		header("Location: signup.php?invalid");
		exit();
	}
	if($pswd != $pswd2) {
		header("Location: signup.php?match");
		exit();
	}

	$pswd = md5(md5(md5($pswd)));

	$query = "INSERT INTO users (email, passwd, threshold) VALUES ('$user', '$pswd', NULL)";
	if(mysqli_query($link, $query) == TRUE) {
		header("Location: index.php?created");
		exit();
	} else {
		header("Location: signup.php?exists");
		exit();
	}
}

function getBidState($link) {
	try {
		$query = "SELECT value, users.UID, email FROM bid_state AS bid LEFT OUTER JOIN users on bid.UID = users.UID";
		$result = mysqli_query($link, $query);
		if($result == false)
			throw new Exception("Error Processing Request");
		$array = mysqli_fetch_assoc($result);
		return $array;
	} catch (Exception $e) {
		echo($e->getMessage());
	}
}

function getUserThreshold($link, $user) {
	try {
		$query = "SELECT threshold FROM users WHERE UID=" . $user;
		$rs = mysqli_query($_SESSION['link'], $query);
		if($rs == false)
			throw new Exception("Error Processing Request");
		$threshold = mysqli_fetch_assoc($rs)['threshold'];
		return $threshold;
	} catch (Exception $e) {
		echo($e->getMessage());
	}
}

?>
