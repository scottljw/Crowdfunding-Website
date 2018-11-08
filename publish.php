<!DOCTYPE html>
<html>
<head>
	<title>Crowdfunding: Publishing Project</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>li {list-style: none;}</style>
</head>
<body>
	<h2>Publishing Project</h2>
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
		$current_date = date("Y-m-d");
		if ($_SESSION[userid] == NULL) {
			echo "Please login to publish a project.";
		}
		else {
	?>
	<form name="display" action="publish.php" method="POST">
		<ul>
			<div class="container">
				<li><label for="title">Title</label></li>
				<li><input type="text" placeholder="Enter Project Title" name="title" required></li>
				<li><label for="desc">Description</label></li>
				<li><input type="text" placeholder="Enter Description" name="desc" required></li>
				<li><label for="dura">Duration (Days)</label></li>
				<li><input type="number" placeholder="Enter Funding Duration" name="dura" required></li>
				<li><label for="cat">Category</label></li>
				<li><input type="text" placeholder="Enter Category" name="cat" required></li>
				<li><label for="total">Total Amount (USD)</label></li>
				<li><input type="number" placeholder="Enter Total Amount" name="total" required></li>
			</div>
			<div class="clearfix">
				<button type="reset" class="cancelbtn">Clear</button>
				<button type="submit" class="publishbtn" name="pub">Confirm Pulish</button>
			</div>
		</ul>
	</form>
	<br/>
	<?php
		}
		if (isset($_POST[pub])) {
			$id = pg_fetch_assoc(pg_query("SELECT COUNT(*) AS num FROM publish_projects"))[num] + 1;
			$result = pg_query($db, "INSERT INTO publish_projects VALUES ('$_SESSION[userid]', '$id', '$_POST[title]', '$_POST[desc]', '$current_date', '$_POST[dura]', '$_POST[cat]', '$_POST[total]')");
			if (!$result) {
				echo "<p>Invalid input(s)!</p>";
			}
			else {
				echo "<p>Publish successful!</p>";
				echo "<p>Your Project ID is ". $id .".</p>";
			}
		}
	?>
</body>
</html>
