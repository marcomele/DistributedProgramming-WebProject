<?php
	include("verify.php");

	if(isset($_POST['submit'])) {
		$_SESSION['thr'] = $_POST['threshold'];
		include("update.php");
	}

	$queryBid = "SELECT * FROM bid_state";
	$rs = mysqli_query($_SESSION['link'], $queryBid);
	$array = mysqli_fetch_assoc($rs);
	$bid = $array['value'];
	$bidder = $array['UID'];
	$query = "SELECT threshold FROM users WHERE UID=" . $_SESSION['UID'];
	$rs = mysqli_query($_SESSION['link'], $query);
	$_SESSION['thr'] = mysqli_fetch_assoc($rs)['threshold'];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Auction Personal Area</title>
		<link rel="stylesheet" type="text/css" href="css/4.1.1.css" />
	</head>
	<body>
		<h1>Welcome <?php echo($_SESSION['username']); ?>!</h1>
		<h2>This is your personal area</h2>
		<div class="main-content">
			<div class="menu">
				<nav>
					<ul>
						<li><a href="index.php">Home</a></li>
						<?php if(!isset($_SESSION['authorized'])): ?>
							<li><a href="signup.php">Sign up</a></li>
						<?php endif; ?>
						<?php if(isset($_SESSION['authorized'])): ?>
							<li><a href="logout.php">Logout</a></li>
						<?php endif; ?>
					</ul>
				</nav>
			</div>
			<div id="content" class="page">
				<h3>The current auction bid is</h3>
				<div class="bid">$
					<?php echo(number_format($bid, 2, ".", ",")); ?>
				</div>
				<?php
					if($_SESSION['thr'] == NULL)
						echo("<div class='unset'>You have not set a threshold yet.</div>");
					else {
						if($bidder == $_SESSION['UID'])
							echo("<div class='bidder'>You are the highest bidder!");
						else
							echo("<div class='non-bidder'>Bid exceeded!");
						echo("<br  /><br />Your current threshold is $ " . number_format($_SESSION['thr'], 2, ".", ",") . "</div>");
					}
				?>
				<div class="actions">
					<form name="update" method="post" action="secured.php">
						<label>Update your threshold</label><br />
						<input type="number" name="threshold" value=<?php $next = ($_SESSION['thr'] == NULL) ? $bid + 0.01 : $_SESSION['thr'] + 0.01; echo("'$next'"); ?> step="0.01" min=<?php echo("'$next'"); ?>autofocus="true"/>
						<input type="submit" name="submit" value="Set" />
					</form>
				</div>
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
