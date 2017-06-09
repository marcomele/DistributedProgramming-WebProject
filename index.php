<?php
	session_start();
	include("expire.php");
	if(isset($_SESSION['authorized'])) {
		header("HTTP/1.1 307 Temporary Redirect");
		header("Location: secured.php");
	}
	if(isset($_POST['submit'])) {
		include("connect.php");
		include("attempt.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Auction Home Page</title>
		<link rel="stylesheet" type="text/css" href="css/4.1.1.css" />
	</head>
	<body>
		<h1>Welcome to this auction!</h1>

		<div class="main-content">
			<div class="menu">
				<nav>
					<ul>
						<li><a href="index.php">Home</a></li>
						<?php if(!isset($_SESSION['authorized'])): ?>
							<li><a href="signup.php">Sign up</a></li>
						<?php endif; ?>
						<?php if(isset($_SESSION['authorized'])) :?>
							<li><a href="logout.php">Logout</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
			<div id="content" class="page">
				<h2>Login</h2>
				<form name="form-login" method="post" action="index.php">
					<label>Username:</label><br />
					<input name="user" type="email" /><br />
					<label>Password: </label><br />
					<input name="passwd" type="password" /><br />
					<input name="submit" type="submit" value="Log in" />
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
