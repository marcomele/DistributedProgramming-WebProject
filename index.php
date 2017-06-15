<?php
	/* HTTPS redirect */
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') {
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header("HTTP/1.1 301 Moved permanently");
		header("Location: " . $redirect);
	}
	session_start();
	if(isset($_SESSION['authorized']))
		include("expire.php");
	// if(isset($_SESSION['authorized'])) {
	// 	header("HTTP/1.1 307 Temporary Redirect");
	// 	header("Location: secured.php");
	// }
	include("connect.php");
	$query = "SELECT * FROM bid_state LEFT OUTER JOIN users ON bid_state.UID = users.UID";
	$result = mysqli_query($_SESSION['link'], $query);
	$array = mysqli_fetch_assoc($result);
	$bid = $array['value'];
	$bidder = $array['UID'];
	$bidderEmail = $array['email'];
	if(isset($_POST['submit'])) {
		include("attempt.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Auction Home Page</title>
		<link rel="stylesheet" type="text/css" href="css/4.1.1.css" />
		<script type="text/javascript" src="controls.js"></script>
		<noscript><div class="msg">
			Warning: this site will not work properly unless JavaScript is enabled. Please enable javascript in your browser settings.
		</div></noscript>
	</head>
	<body>
		<header><table>
			<tr>
				<td class="header">
					<h1>Welcome <?php if (isset($_SESSION['authorized'])): ?>
						<?php echo($_SESSION['username']); ?>!</h1>
					<?php else: ?>
						to this auction!</h1>
					<?php endif; ?>
					<?php if (isset($_GET['expired'])): ?>
						<div class="msg">
							Your session has expired, please log in again.
						</div>
					<?php endif; ?>
					<?php if (isset($_GET['created'])): ?>
						<div class="bidder">
							Your account has been created, you can now log in.
						</div>
					<?php endif; ?>
					<?php if (isset($_SESSION['attempt']) && !isset($_SESSION['authorized'])): ?>
						<div class="non-bidder">
							Login error: wrong username or password.
						</div>
						<?php unset($_SESSION['attempt']) ?>
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
				<table>
					<tr>
					<td class="blank">
						<table class="form-table">
							<h2>The current auction bid value is</h2>
							<tr>
								<td class="info">
									<div class="bid">
										$ <?php echo(number_format($bid, 2, ".", ",")); ?>
									</div>
								</td>
							</tr>

						</table>
						<?php if($bidder == NULL): ?>
							<h3>No user is currently holding the bid</h3>
						<?php else: ?>
							<h3>held by user
								<?php
								echo "<a class='mono' href='mailto:" . $bidderEmail . "'>" . $bidderEmail . "</a>";
								?>
							</h3>
						<?php endif; ?>
					</td>
					<?php if (!isset($_SESSION['authorized'])): ?>
						<td class="blank">
							<h2>Login to partecipate!</h2>

							<form name="form-login" method="post" action="index.php">
								<table class="form-table">
									<span class="subtitle">
										Don't have an account? <a href="signup.php" class="underdotted">Sign up here</a>!<br />
									</span>
									<tr>
										<td class="fieldname"><label>Username</label></td>
										<td class="fieldinput"><input name="user" type="email" placeholder="email@example.org"/></td>
									</tr>
									<tr>
										<td class="fieldname"><label>Password</label></td>
										<td class="fieldinput"><input name="passwd" type="password" placeholder="password"/></td>
									</tr>
								</table>
								<input name="submit" type="submit" value="Log in" />
							</form>
						</td>
					<?php else: ?>
						<td align-content="center" class="blank">
							<h2 word-wrap="break-word">
								To set your threshold and take part to the auction go to your <a href="secured.php" >Personal Page</a>!
							</h2>
						</td>
					<?php endif; ?>

					</tr>
				</table>
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
						<a href="https://it.linkedin.com/in/marco-mele/en" target="_blank" class="mono">linkedin.com/in/marco-mele</a>
					</td>
				</tr>
			</table>
		</footer>
	</body>
</html>
