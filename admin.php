<!DOCTYPE html>
<html>
<head>
	<title>Crowdfunding: Administrating Website</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>li {list-style: none;}</style>
</head>
<body>
	<?php
		session_start();
		if ($_COOKIE[userid] != NULL && $_SESSION[userid] == NULL) {
			$_SESSION[userid] = $_COOKIE[userid];
		}
		// Connect to the database. Please change the password in the following line accordingly
		$db = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=000000");
		date_default_timezone_set("Asia/Singapore");
		$current_time = date("Y-m-d H:i:s");
		if ($_SESSION[userid] == NULL || pg_fetch_result(pg_query($db, "SELECT role FROM users WHERE user_id = '$_SESSION[userid]'"), 0, 0) != 1) {
			header('Location: index.php');
		}
		$users = pg_query($db, "SELECT * FROM users");
		$projects = pg_query($db, "SELECT * FROM publish_projects");
		$funds = pg_query($db, "SELECT * FROM fund");
	?>
	<form name="display" action="index.php" method="GET">
		<button type="submit">Return to Homepage</button>
	</form>
	<ul>
		<li><h2>Users</h2></li>
		<table>
			<th>Index</th>
			<th>User ID</th>
			<th>Full Name</th>
			<th>Email</th>
			<th>Date of Registration</th>
			<th>Role</th>
			<?php while ($row = pg_fetch_assoc($users)) { ?>
				<tr>
					<td align='center' width='200'> <?php echo $row['user_id'] ?> </td>
					<td align='center' width='200'> <?php echo $row['name'] ?> </td>
					<td align='center' width='200'> <?php echo $row['email'] ?> </td>
					<td align='center' width='200'> <?php echo $row['date_of_registration'] ?> </td>
					<td align='center' width='200'> <?php echo ($row['role'] ? "admin" : "member") ?> </td>
				</tr>
			<?php } ?>
		</table>
		<form name="cuser" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="cuserbtn" name="cuser">Create</button>
				<label for="cuid"><b>User ID</b></label>
    			<input type="text" name="cuser_id" required>
    			<label for="cpsw"><b>Password</b></label>
    			<input type="password" name="cpassword" required>
    			<label for="cname"><b>Full Name</b></label>
    			<input type="text" name="cname" required>
				<label for="cemail"><b>Email</b></label>
				<input type="text" name="cemail" required>
				<label for="cdor"><b>Date of Registration</b></label>
    			<input type="text" name="cdate_of_registration" required>
				<label for="crole"><b>Role</b></label>
    			<input type="text" name="crole" required>
			</div>
		</form>
		<form name="uuser" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="uuserbtn" name="uuser">Modify</button>
				<label for="uuid"><b>User ID</b></label>
    			<input type="text" name="uuser_id">
    			<label for="uname"><b>Full Name</b></label>
    			<input type="text" name="uname">
				<label for="uemail"><b>Email</b></label>
				<input type="text" name="uemail">
				<label for="udor"><b>Date of Registration</b></label>
    			<input type="text" name="udate_of_registration">
				<label for="urole"><b>Role</b></label>
    			<input type="text" name="urole">
				<label for="uiu"><b>index</b></label>
    			<input type="text" name="iuser" required>
			</div>
		</form>
		<form name="duser" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="duserbtn" name="duser">Delete</button>
				<label for="duid"><b>User ID</b></label>
    			<input type="text" name="duser_id" required>
			</div>
		</form>
		<li><h2>Projects</h2></li>
		<table>
			<th>Project ID</th>
			<th>Title</th>
			<th>Description</th>
			<th>Date of Publish</th>
			<th>Duration</th>
			<th>Category</th>
			<th>Total Amount</th>
			<th>Current Amount</th>
			<th>Publisher</th>
			<?php while ($row = pg_fetch_assoc($projects)) { ?>
				<tr>
					<td align='center' width='200'> <?php echo $row['project_id'] ?> </td>
					<td align='center' width='200'> <?php echo $row['title'] ?> </td>
					<td align='center' width='200'> <?php echo $row['description'] ?> </td>
					<td align='center' width='200'> <?php echo $row['start_date'] ?> </td>
					<td align='center' width='200'> <?php echo $row['duration'] ?> days </td>
					<td align='center' width='200'> <?php echo $row['category'] ?> </td>
					<td align='center' width='200'> $<?php echo $row['total_amount'] ?> </td>
					<td align='center' width='200'> $<?php echo $row['current_amount'] ?> </td>
					<td align='center' width='200'> <?php echo $row['publisher'] ?> </td>
				</tr>
			<?php } ?>
		</table>
		<form name="cproject" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="cprojectbtn" name="cproject">Create</button>
				<label for="cpid"><b>Project ID</b></label>
    			<input type="text" name="cproject_id" required>
    			<label for="cttl"><b>Title</b></label>
    			<input type="text" name="ctitle" required>
    			<label for="cdes"><b>Description</b></label>
    			<input type="text" name="cdescription" required>
				<label for="cdop"><b>Date of Publish</b></label>
				<input type="text" name="cstart_date" required>
				<label for="cdura"><b>Duration</b></label>
    			<input type="number" name="cduration" required>
    			<label for="ccat"><b>Category</b></label>
    			<input type="text" name="ccategory" required>
				<label for="cttn"><b>Total Amount</b></label>
    			<input type="number" name="ctotal_amount" required>
    			<label for="ccrtn"><b>Current Amount</b></label>
    			<input type="number" name="ccurrent_amount" required>
    			<label for="cpubr"><b>Publisher</b></label>
    			<input type="text" name="cpublisher" required>
			</div>
		</form>
		<form name="uproject" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="uprojectbtn" name="uproject">Modify</button>
				<label for="upid"><b>Project ID</b></label>
    			<input type="text" name="uproject_id" required>
    			<label for="uttl"><b>Title</b></label>
    			<input type="text" name="utitle" required>
    			<label for="udes"><b>Description</b></label>
    			<input type="text" name="udescription" required>
				<label for="udop"><b>Date of Publish</b></label>
				<input type="text" name="ustart_date" required>
				<label for="udura"><b>Duration</b></label>
    			<input type="number" name="uduration" required>
    			<label for="ucat"><b>Category</b></label>
    			<input type="text" name="ucategory" required>
				<label for="uttn"><b>Total Amount</b></label>
    			<input type="number" name="utotal_amount" required>
    			<label for="ucrtn"><b>Current Amount</b></label>
    			<input type="number" name="ucurrent_amount" required>
    			<label for="upubr"><b>Publisher</b></label>
    			<input type="text" name="upublisher" required>
			</div>
		</form>
		<form name="dproject" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="dprojectbtn" name="dproject">Delete</button>
				<label for="dpid"><b>Project ID</b></label>
    			<input type="text" name="dproject_id" required>
			</div>
		</form>
		<li><h2>Funding Records</h2></li>
		<table>
			<th>User ID</th>
			<th>Project ID</th>
			<th>Fund Time</th>
			<th>Amount</th>
			<?php while ($row = pg_fetch_assoc($funds)) { ?>
				<tr>
					<td align='center' width='200'> <?php echo $row['user_id'] ?> </td>
					<td align='center' width='200'> <?php echo $row['project_id'] ?> </td>
					<td align='center' width='200'> $<?php echo $row['fund_time'] ?> </td>
					<td align='center' width='200'> $<?php echo $row['amount'] ?> </td>
				</tr>
			<?php } ?>
		</table>
		<form name="cfund" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="cfundbtn" name="cfund">Create</button>
				<label for="cfuid"><b>User ID</b></label>
    			<input type="text" name="cfuser_id" required>
    			<label for="cfpid"><b>Project ID</b></label>
    			<input type="text" name="cfproject_id" required>
    			<label for="cfdor"><b>Funding Time</b></label>
    			<input type="text" name="cfund_time" required>
				<label for="cfam"><b>Amount</b></label>
    			<input type="number" name="camount" required>
			</div>
		</form>
		<form name="ufund" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="ufundbtn" name="ufund">Modify</button>
				<label for="ufuid"><b>User ID</b></label>
    			<input type="text" name="ufuser_id">
    			<label for="ufpid"><b>Project ID</b></label>
    			<input type="text" name="ufproject_id">
    			<label for="ufdor"><b>Funding Time</b></label>
    			<input type="text" name="ufund_time">
				<label for="ufam"><b>Amount</b></label>
    			<input type="number" name="uamount">
				<label for="ofuid"><b>Old User ID</b></label>
    			<input type="text" name="ofuser_id" required>
    			<label for="ofpid"><b>Old Project ID</b></label>
    			<input type="text" name="ofproject_id" required>
    			<label for="ofdor"><b>Old Funding Time</b></label>
    			<input type="text" name="ofund_time" required>
			</div>
		</form>
		<form name="dfund" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="dfundbtn" name="dfund">Delete</button>
				<label for="dfuid"><b>User ID</b></label>
    			<input type="text" name="dfuser_id" required>
    			<label for="dfpid"><b>Project ID</b></label>
    			<input type="text" name="dfproject_id" required>
    			<label for="dfdor"><b>Funding Time</b></label>
    			<input type="text" name="dfund_time" required>
			</div>
		</form>
	</ul>
	
</body>
</html>