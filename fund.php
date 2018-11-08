<!DOCTYPE html>
<html>
<head>
	<title>Crowdfunding: Funding Project</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>li {list-style: none;}</style>
</head>
<body>
	<h2>Funding Project</h2>
	<form name="display" action="index.php" method="GET">
		<button type="submit">Return to Homepage</button>
	</form>
	<div class="member-dashboard">
		<?php
			session_start();
			if ($_COOKIE[userid] != NULL && $_SESSION[userid] == NULL) {
				$_SESSION[userid] = $_COOKIE[userid];
			}
			if ($_SESSION[userid] == NULL) {
				echo "You have not logged in yet";
			}
			else {
				echo "You have logged in as <i>" . $_SESSION[userid] . "</i>";
			}
		?>
	</div>
	<br/>
	<?php
		// Connect to the database. Please change the password in the following line accordingly
		$db = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=000000");
		date_default_timezone_set("Asia/Singapore");
		$current_time = date("Y-m-d H:i:s");
		if ($_POST[project] != NULL) {
			$_SESSION[project] = $_POST[project];
		}
		if ($_SESSION[userid] == NULL) {
			echo "Please login to fund the project.";
		}
		else {
			$inf = pg_fetch_assoc(pg_query($db, "SELECT * FROM publish_projects WHERE project_id = '$_SESSION[project]'"));
			if ($inf[project_id] == NULL) {
				echo "Proejct does not exist! Please return to homepage.";
			}
			else {
	?>
	<table>
		<ul>
			<li>Project ID: #<?php echo $inf[project_id]?></li>
			<li>Title: <?php echo $inf[title]?></li>
			<li>Description: <?php echo $inf[description]?></li>
			<li>Start Date: <?php echo $inf[start_date]?></li>
			<li>Duration: <?php echo $inf[duration]?> days</li>
			<li>Category: <?php echo $inf[category]?></li>
			<li>amount seeked to fund: $<?php echo $inf[total_amount]?></li>
			<li>Amount already funded: $<?php echo $inf[current_amount]?></li>
		</ul>
	</table>
	
	<?php
				$expire = date_create($inf[start_date]);
				date_add($expire, date_interval_create_from_date_string($inf[duration] . "days"));
				$expire_date = date_format($expire, "Y-m-d");				
				if ($expire_date > $current_time) {
					
	?>
	<form name="display" action="fund.php" method="POST">
		<ul>
			<div class="container">
				<li><label for="am">Amount to Donate (USD)</label></li>
				<li><input type="number" placeholder="Enter Donation Amount" name="am" required></li>
			</div>
			<div class="clearfix">
				<button type="reset" class="cancelbtn">Clear</button>
				<button type="submit" class="fundbtn" name="fund">Confirm Donate</button>
			</div>
		</ul>
	</form>
	<br/>
	<?php
				}
				else echo "The period of funding for this project ended on " . $expire_date;
			}
		}
		if (isset($_POST[fund])) {
			$result = pg_query($db, "INSERT INTO fund VALUES ('$_SESSION[userid]', '$inf[project_id]', '$current_time', '$_POST[am]')");
			if (!$result) {
				echo "<p>Invalid input(s)!</p>";
			}
			else {
				$update = pg_query($db, "UPDATE publish_projects SET current_amount = current_amount + '$_POST[am]' WHERE project_id = '$inf[project_id]'");
				echo "<p>Donate successful!</p>";
				echo "<p>The project now has " . pg_fetch_assoc(pg_query("SELECT current_amount FROM publish_projects WHERE project_id = '$_SESSION[project]'"))[current_amount] . ".</p>";
			}
		}
	?>
</body>
</html>
