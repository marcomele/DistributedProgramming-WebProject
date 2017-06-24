<?php
	session_start();
	include("library.php");
	showPhpErrors();
	checkSessionExpiral();
	$link = connect();
	restrict();

	if(isset($_POST['set'])) {
		update($link, $_POST['threshold'], $_SESSION['UID']);
		unset($_POST['set']);
	}

	$array = getBidState($link);
	$bid = $array['value'];
	$bidder = $array['UID'];
	$bidderEmail = $array['email'];
	$threshold = getUserThreshold($link, $_SESSION['UID']);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Auction Personal Page</title>
		<link rel="stylesheet" type="text/css" href="css/4.1.1.css" />
		<script type="text/javascript" src="controls.js"></script>
		<noscript><div class="jsmsg">
			Warning: this site will not work properly unless JavaScript is enabled. Please enable Javascript in your browser settings.
		</div></noscript>
	</head>
	<body>
		<header><table>
			<tr>
				<td class="header">
					<h1>Welcome <?php echo($_SESSION['username']); ?>!</h1>
					<h2>This is your personal area</h2>
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



						</td>
						<td class="blank">
							<?php
							if($threshold != NULL)
								echo("<table class='form-table'><h2>Your current threshold is</h2><tr><td class='info'><div class='bid'>$ " . number_format($threshold, 2, ".", ",") . "</div></td></tr></table>");
							?>

						</td>
					</tr>
					<tr>
						<td class="blank-bottom">
							<?php if($bidder == NULL): ?>
								<h3>No user is currently holding the bid</h3>
							<?php else: ?>
								<h3>held by user
									<?php
									echo "<a class='mono' href='mailto:" . $bidderEmail . "'>" . $bidderEmail . "</a>";
									?>
								</h3>
							<?php endif; ?>
							<?php
								if($threshold == NULL)
								echo("<div class='unset'>You have not set a threshold yet.</div>");
								else {
									if($bidder == $_SESSION['UID'])
									echo("<div class='bidder'>You are the highest bidder!");
									else
									echo("<div class='non-bidder'>Bid exceeded!");
								}
							?>
						</td>
						<td class="blank-bottom">
							<div class="actions" width="100%">
								<form name="update" method="post" action="secured.php">
									<table class="form-table" width="auto">
										<tr>
											<td class="longfieldname">
												<label>Update your threshold</label>
											</td><td class="fieldinput">
												<input type="number" name="threshold" value=<?php $next = ($threshold == NULL || $bid > $threshold) ? $bid + 0.01 : $threshold + 0.01; echo("'$next'"); ?> step="0.01" min=<?php echo("'$next'"); ?>autofocus="true"/>
											</td><td class="none">
												<input type="submit" name="set" value="Set" />
											</td>
											<?php if (isset($_GET['below'])): ?>
												<div class="msg">
													You can not set a threshold not greater than the current bid value.
												</div>
											<?php endif; ?>
											<?php if (isset($_GET['error'])): ?>
												<div class="non-bidder">
													Unable to carry the operation: internal server error.
												</div>
											<?php endif; ?>
										</tr>
									</table>
								</form>
							</div>

						</td>
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
						<a href="https://it.linkedin.com/in/marco-mele/en" class="mono">linkedin.com/in/marco-mele</a>
					</td>
				</tr>
			</table>
		</footer>
	</body>
</html>
