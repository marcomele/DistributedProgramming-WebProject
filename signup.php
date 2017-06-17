<?php
	session_start();
	/* HTTPS redirect */
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved permanently");
		header("Location: " . $redirect);
		exit();
	}
	if(isset($_POST['registrar'])) {
		include("connect.php");
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$user = sanitize($_POST['user']);
		$pswd = sanitize($_POST['passwd']);
		$pswd2 = sanitize($_POST['passwd2']);

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
		if(mysqli_query($_SESSION['link'], $query) == TRUE) {
			header("Location: index.php?created");
			exit();
		} else {
			header("Location: signup.php?exists");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Auction Sign Up</title>
		<link rel="stylesheet" type="text/css" href="css/4.1.1.css" />
		<script type="text/javascript">
			function match() {
				if (document.getElementById("pswd1").value != document.getElementById("pswd2").value)
					document.getElementById("pswd2").setCustomValidity('Error: passwords musth match.');
				else {
					document.getElementById("pswd2").setCustomValidity('');
					document.getElementById("pswd1").setCustomValidity('');
				}
			}
		</script>
		<script type="text/javascript" src="controls.js"></script>
		<noscript><div class="jsmsg">
			Warning: this site will not work properly unless JavaScript is enabled. Please enable Javascript in your browser settings.
		</div></noscript>
	</head>
	<body>
		<header><table>
			<tr>
				<td class="header">
					<h1>Sign up for this auction!</h1>
					<?php if (isset($_GET['email'])): ?>
						<div class="non-bidder">
							You must enter a valid email address.
						</div>
					<?php endif; ?>
					<?php if (isset($_GET['invalid'])): ?>
						<div class="non-bidder">
							Password must contain at least one digit and one number.
						</div>
					<?php endif; ?>
					<?php if (isset($_GET['match'])): ?>
						<div class="non-bidder">
							Passwords must match.
						</div>
					<?php endif; ?>
					<?php if (isset($_GET['exists'])): ?>
						<div class="unset">
							The provided email address already refers to an account, you may want to login instead.
						</div>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		</header>

		<div class="main-content">
			<div class="menu">
				<nav>
					<ul>
						<li><a href="index.php">Home</a></li>
						<?php if(!isset($_SESSION['authorized'])): ?>
							<li><a href="signup.php">Sign up</a></li>
						<?php endif; ?>
						<?php if(isset($_SESSION['authorized'])) :?>
							<li><a href="secured.php">Personal Page</a></li>
						<?php endif; ?>
						<?php if(isset($_SESSION['authorized'])) :?>
							<li><a href="logout.php">Logout</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
			<div id="content" class="page">
				<h2>Sign up</h2>
				<form name="form-signup" method="post" action="signup.php">
					<table class="form-table">
						<tr <?php if (isset($_GET['email'])): ?>
							style="height: 3.3em; vertical-align: bottom; border-bottom: solid 3px #C62828;"
						<?php endif; ?> <?php if (isset($_GET['exists'])): ?>
							style="height: 3.3em; vertical-align: bottom; border-bottom: solid 3px #FFA000;"
						<?php endif; ?>>
							<td class="fieldname">
								<label>Email<br /><span class="subtitle">
									Will also be your username
								</span></label><br />
							</td><td class="fieldinput">
								<input name="user" type="email" required placeholder="email@example.org"/>
							</td>
						</tr>
						<tr <?php if (isset($_GET['invalid'])): ?>
							style="height: 3.3em; vertical-align: bottom; border-bottom: solid 3px #C62828;"
						<?php endif; ?>>
							<td class="fieldname">
								<label>Password<br /><span class="subtitle">
									At least one letter and one digit
								</span></label>
							</td><td class="fieldinput">
								<input id="pswd1" name="passwd" type="password" oninput="match()" pattern="^.*(?=.*[0-9])(?=.*[a-zA-Z]).*$" required placeholder="password"/><br />
							</td>
						</tr>
						<tr <?php if (isset($_GET['invalid']) || isset($_GET['match'])): ?>
							style="height: 3.3em; vertical-align: bottom; border-bottom: solid 3px #C62828;"
						<?php endif; ?>>
							<td class="fieldname">
								<label>Repeat password </label>
							</td>
							<td class="fieldinput">
								<input id="pswd2" name="passwd2" type="password" required oninput="match()" placeholder="repeat password"/>
							</td>
						</tr>
					</table>
					<input name="registrar" type="submit" value="Sign Up!" />
				</form>
			</div>
		</div>


		<div class="prefoot"></div>
		<footer>
			<table class="footer">
				<tr class="footer-top">
					<td class="left-footer">
						Marco Mele <br />
						Student ID: 241882<br />
						<a href="https://www.polito.it" target="_blank">Polytechnic University of Turin</a> <br />
					</td>
					<td class="right-footer">
						<a href="mailto:mmele2@uic.edu" class="mono">mmele2@uic.edu</a><br />
						<a href="mailto:m.mele@studenti.polito.it" class="mono">m.mele@studenti.polito.it</a><br />
						<a href="https://it.linkedin.com/in/marco-mele/en" class="mono">linkedin.com/in/marco-mele</a>
					</td>
				</tr>
			</table>
		</footer>
	</body>
</html>
