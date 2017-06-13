<?php
	session_start();
	/* HTTPS redirect */
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved permanently");
		header("Location: " . $redirect);
	}
	if(isset($_POST['submit'])) {
		include("connect.php");
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$user = mysqli_real_escape_string($connect, $_POST['user']);
		$pswd = mysqli_real_escape_string($connect, $_POST['passwd']);
		$pswd = md5(md5(md5($pswd)));

		$query = "INSERT INTO users (email, passwd, threshold) VALUES ('$user', '$pswd', NULL)";
		if(mysqli_query($_SESSION['link'], $query) == TRUE) {
			header("HTTP/1.1 307 Temporary Redirect");
			header("Location: index.php");
		}
		echo("<script type='text/javascript'>alert('The email $user has already been used.')</script>");
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
				else
					document.getElementById("pswd2").setCustomValidity('');
			}
		</script>
	</head>
	<body>
		<h1>Sign up for this auction!</h1>

		<div class="main-content">
			<div class="menu">
				<nav>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="signup.php">Sign up</a></li>
						<?php if(isset($_SESSION['authorized'])) :?>
							<li><a href="logout.php">Logout</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
			<div id="content" class="page">
				<h2>Sign up</h2> <!-- TODO: password strength control -->
				<form name="form-signup" method="post" action="signup.php">
					<table class="form-table">
						<tr>
							<td class="fieldname">
								<label>Email<br /><span class="subtitle">
									will also be your username
								</span></label><br />
							</td><td class="fieldinput">
								<input name="user" type="email" required/>
							</td>
						</tr>
						<tr>
							<td class="fieldname">
								<label>Password </label>
							</td><td class="fieldinput">
								<input id="pswd1" name="passwd" type="password" pattern="^.*(?=.*[0-9])(?=.*[a-z]).*$" required/><br />
							</td>
						</tr>
						<tr>
							<td class="fieldname">
								<label>Repeat password </label>
							</td>
							<td class="fieldinput">
								<input id="pswd2" name="passwd2" type="password" required oninput="match()"/>
							</td>
						</tr>
					</table>
					<input name="submit" type="submit" value="Sign Up!" />
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
