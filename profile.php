<!DOCTYPE html>
<html>
<head>
	<title>Crowdfunding: Viewing Profile</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>li {list-style: none;}</style>
</head>
<body>
	<h2>Viewing Profile</h2>
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
		if ($_SESSION[userid] == NULL) {
			echo "Please login to view your profile.";
		}
		else {
			$profile = pg_fetch_assoc(pg_query($db, "SELECT * FROM users WHERE users.user_id = '$_SESSION[userid]'"));
			$pub = pg_query($db, "SELECT p.project_id, p.title, p.category, p.total_amount, p.total_amount-p.current_amount AS shortage FROM Publish_Projects p INNER JOIN Users u ON u.user_id = p.publisher WHERE u.user_id = '$_SESSION[userid]'");
			$fun = pg_query($db, "SELECT p.project_id, p.title, f.amount, f.fund_time FROM Publish_Projects p NATURAL JOIN Fund f NATURAL JOIN Users u WHERE u.user_id = '$_SESSION[userid]'");
			$personal_sum = pg_fetch_result(pg_query($db, "SELECT SUM(f.amount) AS out FROM fund f NATURAL JOIN users u WHERE u.user_id = '$_SESSION[userid]'"), 0, 0);
	?>
	<table>
		<ul>
			<li>User ID: <?php echo $profile[user_id]?></li>
			<li>Name: <?php echo $profile[name]?></li>
			<li>Email: <?php echo $profile[email]?></li>
			<li>Registration Date: <?php echo $profile[date_of_registration]?></li>
		</ul>
		<!-- Button to open the modal form -->
		<button onclick="document.getElementById('change').style.display='block'">Change profile</button>
		<div id="change" class="modal">
			<span onclick="document.getElementById('change').style.display='none'" class="close" title="Close Modal">
				&times;
			</span>
			<form name="modify" action="profile.php" method="POST">
				<h3>Modify Personal Information</h3>
				<div class="container">
	    			<label for="psw"><b>Password</b></label>
	    			<input type="password" placeholder="Enter Password" name="psw" value="<?php echo $profile[password];?>" required>
	    			<label for="name"><b>Full Name</b></label>
	    			<input type="text" placeholder="Enter Full Name" name="name" value="<?php echo $profile[name];?>" required>
					<label for="email"><b>Email</b></label>
					<input type="text" placeholder="Enter Email" name="email" value="<?php echo $profile[email];?>" required>
				</div>
				<div class="clearfix">
					<button type="button" class="cancelbtn">Cancel</button>
					<button type="submit" class="changebtn" name="mod">Submit</button>
				</div>
			</form>
			<br/>
		</div>
	</table>
	<h3>Projects you published</h3>
	<table>
		<th>Project ID</th>
		<th>Title</th>
		<th>Category</th>
		<th>Total Amount</th>
		<th>Shortage</th>
		<?php while ($row = pg_fetch_assoc($pub)) { ?>
			<tr>
				<td align='center' width='200'> <?php echo $row['project_id'] ?> </td>
				<td align='center' width='200'> <?php echo $row['title'] ?> </td>
				<td align='center' width='200'> <?php echo $row['category'] ?> </td>
				<td align='center' width='200'> $<?php echo $row['total_amount'] ?> </td>
				<td align='center' width='200'> $<?php echo $row['shortage'] ?> </td>
			</tr>
		<?php } ?>
	</table>
	<h3>Projects you donated</h3>
	<table>
		<th>Project ID</th>
		<th>Title</th>
		<th>Amount</th>
		<th>Donating Time</th>
		<?php while ($row = pg_fetch_assoc($fun)) { ?>
			<tr>
				<td align='center' width='200'> <?php echo $row['project_id'] ?> </td>
				<td align='center' width='200'> <?php echo $row['title'] ?> </td>
				<td align='center' width='200'> $<?php echo $row['amount'] ?> </td>
				<td align='center' width='200'> <?php echo $row['fund_time'] ?> </td>
			</tr>
		<?php } ?>
	</table>
	<br/>
	<?php
			echo $personal_sum ? "You have donated a total amount of $" . $personal_sum : "You have not made any donation";
		}
		if (isset($_POST[mod])) {
			$res = pg_query($db, "UPDATE users SET password = '$_POST[psw]', name = '$_POST[name]', email = '$_POST[email]' WHERE user_id = '$_SESSION[userid]'");
			if (!$res) {
				echo "<p>Invalid input(s)!</p>";
			}
			else {
				echo "<p>Profile update successful!</p>";
			}
		}
	?>
</body>
</html>