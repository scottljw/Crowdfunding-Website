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
		$users = pg_query($db, "SELECT * FROM users ORDER BY user_id");
		$projects = pg_query($db, "SELECT * FROM publish_projects ORDER BY project_id");
		$funds = pg_query($db, "SELECT * FROM fund ORDER BY user_id, project_id, fund_time");
		$effect = -1;
		if ($_SESSION[userid] == NULL || pg_fetch_result(pg_query($db, "SELECT role FROM users WHERE user_id = '$_SESSION[userid]'"), 0, 0) != 1) {
			header('Location: index.php');
		}
		elseif (isset($_POST[cuser])) {
			$effect = pg_query($db, "INSERT INTO users VALUES ('$_POST[cuser_id]', '$_POST[cpassword]', '$_POST[cname]', '$_POST[cemail]', '$_POST[cdate_of_registration]', '$_POST[crole]')");
		}
		elseif (isset($_POST[uuser])) {
			$past = pg_fetch_row($users, $_POST[iuser] - 1);
			$current = array("user_id"=>($_POST[uuser_id] ? $_POST[uuser_id] : $past[0]), "name"=>($_POST[uname] ? $_POST[uname] : $past[2]), "email"=>($_POST[uemail] ? $_POST[uemail] : $past[3]), "date_of_registration"=>($_POST[udate_of_registration] ? $_POST[udate_of_registration] : $past[4]), "role"=>($_POST[urole] ? $_POST[urole] : $past[5]));
			$effect = pg_query($db, "UPDATE users SET user_id = '$current[user_id]', name = '$current[name]', email = '$current[email]', date_of_registration = '$current[date_of_registration]', role = '$current[role]' WHERE user_id = '$past[0]'");
		}
		elseif (isset($_POST[duser])) {
			$effect = pg_query($db, "DELETE FROM users WHERE user_id = '$_POST[duser_id]'");
		}
		elseif (isset($_POST[cproject])) {
			$effect = pg_query($db, "INSERT INTO publish_projects VALUES ('$_POST[cpublisher]', '$_POST[cproject_id]', '$_POST[ctitle]', '$_POST[cdescription]', '$_POST[cstart_date]', '$_POST[cduration]', '$_POST[ccategory]', '$_POST[ctotal_amount]', '$_POST[ccurrent_amount]')");
		}
		elseif (isset($_POST[uproject])) {
			$past = pg_fetch_row($projects, $_POST[iproject] - 1);
			$current = array("publisher"=>($_POST[upublisher] ? $_POST[upublisher] : $past[0]), "project_id"=>($_POST[uproject_id] ? $_POST[uproject_id] : $past[1]), "title"=>($_POST[utitle] ? $_POST[utitle] : $past[2]), "description"=>($_POST[udescription] ? $_POST[udescription] : $past[3]), "start_date"=>($_POST[ustart_date] ? $_POST[ustart_date] : $past[4]), "duration"=>($_POST[uduration] ? $_POST[uduration] : $past[5]), "category"=>($_POST[ucategory] ? $_POST[ucategory] : $past[6]), "total_amount"=>($_POST[utotal_amount] ? $_POST[utotal_amount] : $past[7]), "current_amount"=>($_POST[ucurrent_amount] ? $_POST[ucurrent_amount] : $past[8]));
			$effect = pg_query($db, "UPDATE publish_projects SET publisher = '$current[publisher]', project_id = '$current[project_id]', title = '$current[title]', description = '$current[description]', start_date = '$current[start_date]', duration = '$current[duration]', category = '$current[category]', total_amount = '$current[total_amount]', current_amount = '$current[current_amount]' WHERE project_id = '$past[1]'");
		}
		elseif (isset($_POST[dproject])) {
			$effect = pg_query($db, "DELETE FROM publish_projects WHERE project_id = '$_POST[dproject_id]'");
		}
		elseif (isset($_POST[cfund])) {
			$effect = pg_query($db, "INSERT INTO fund VALUES ('$_POST[cfuser_id]', '$_POST[cfproject_id]', '$_POST[cfund_time]', '$_POST[camount]')");
		}
		elseif (isset($_POST[ufund])) {
			$past = pg_fetch_row($funds, $_POST[ifund] - 1);
			$current = array("user_id"=>($_POST[ufuser_id] ? $_POST[ufuser_id] : $past[0]), "project_id"=>($_POST[ufproject_id] ? $_POST[ufproject_id] : $past[1]), "fund_time"=>($_POST[ufund_time] ? $_POST[ufund_time] : $past[2]), "amount"=>($_POST[uamount] ? $_POST[uamount] : $past[3]));
			$effect = pg_query($db, "UPDATE fund SET user_id = '$current[user_id]', project_id = '$current[project_id]', fund_time = '$current[fund_time]', amount = '$current[amount]' WHERE user_id = '$past[0]' AND project_id = '$past[1]' AND fund_time = '$past[2]'");
		}
		elseif (isset($_POST[dfund])) {
			$effect = pg_query($db, "DELETE FROM fund WHERE user_id = '$_POST[dfuser_id]' AND project_id = '$_POST[dfproject_id]' AND fund_time = '$_POST[dfund_time]'");
		}
		$usersx = pg_query($db, "SELECT * FROM users ORDER BY user_id");
		$projectsx = pg_query($db, "SELECT * FROM publish_projects ORDER BY project_id");
		$fundsx = pg_query($db, "SELECT * FROM fund ORDER BY user_id, project_id, fund_time");
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
			<?php
				$count = 0;
				while ($row = pg_fetch_assoc($usersx)) {
					$count = $count + 1;
			?>
				<tr>
					<td align='center' width='200'> <?php echo $count ?> </td>
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
    			<input type="text" name="cuser_id" size="1" required>
    			<label for="cpsw"><b>Password</b></label>
    			<input type="password" name="cpassword" size="1" required>
    			<label for="cname"><b>Full Name</b></label>
    			<input type="text" name="cname" required>
				<label for="cemail"><b>Email</b></label>
				<input type="text" name="cemail" required>
				<label for="cdor"><b>Date of Registration</b></label>
    			<input type="text" name="cdate_of_registration" required>
				<label for="crole"><b>Role</b></label>
    			<input type="number" name="crole" placeholder="1 as admin" required>
			</div>
		</form>
		<form name="uuser" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="uuserbtn" name="uuser">Modify</button>
				<label for="uuid"><b>User ID</b></label>
    			<input type="text" name="uuser_id" size="1">
    			<label for="upsw"><b>Password</b></label>
    			<input type="password" name="upassword" size="1" disabled>
    			<label for="uname"><b>Full Name</b></label>
    			<input type="text" name="uname">
				<label for="uemail"><b>Email</b></label>
				<input type="text" name="uemail">
				<label for="udor"><b>Date of Registration</b></label>
    			<input type="text" name="udate_of_registration">
				<label for="urole"><b>Role</b></label>
    			<input type="number" name="urole" placeholder="1 as admin">
				<label for="uiu"><b>index</b></label>
    			<input type="text" name="iuser" size="1" required>
			</div>
		</form>
		<form name="duser" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="duserbtn" name="duser">Delete</button>
				<label for="duid"><b>User ID</b></label>
    			<input type="text" name="duser_id" size="1" required>
			</div>
		</form>
		<li><h2>Projects</h2></li>
		<table>
			<th>Index</th>
			<th>Project ID</th>
			<th>Title</th>
			<th>Description</th>
			<th>Date of Publish</th>
			<th>Duration</th>
			<th>Category</th>
			<th>Total Amount</th>
			<th>Current Amount</th>
			<th>Publisher</th>
			<?php
				$count = 0;
				while ($row = pg_fetch_assoc($projectsx)) {
					$count = $count + 1;
			?>
				<tr>
					<td align='center' width='200'> <?php echo $count ?> </td>
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
    			<input type="text" name="cproject_id" size="1" required>
    			<label for="cttl"><b>Title</b></label>
    			<input type="text" name="ctitle" size="1" required>
    			<label for="cdes"><b>Description</b></label>
    			<input type="text" name="cdescription" size="1" required>
				<label for="cdop"><b>Date of Publish</b></label>
				<input type="text" name="cstart_date" required>
				<label for="cdura"><b>Duration</b></label>
    			<input type="text" name="cduration" size="1" required>
    			<label for="ccat"><b>Category</b></label>
    			<input type="text" name="ccategory" size="1" required>
				<label for="cttn"><b>Total Amount $</b></label>
    			<input type="text" name="ctotal_amount" size="1" required>
    			<label for="ccrtn"><b>Current Amount $</b></label>
    			<input type="text" name="ccurrent_amount" size="1" required>
    			<label for="cpubr"><b>Publisher</b></label>
    			<input type="text" name="cpublisher" size="1" required>
			</div>
		</form>
		<form name="uproject" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="uprojectbtn" name="uproject">Modify</button>
				<label for="upid"><b>Project ID</b></label>
    			<input type="text" name="uproject_id" size="1">
    			<label for="uttl"><b>Title</b></label>
    			<input type="text" name="utitle" size="1">
    			<label for="udes"><b>Description</b></label>
    			<input type="text" name="udescription" size="1">
				<label for="udop"><b>Date of Publish</b></label>
				<input type="text" name="ustart_date">
				<label for="udura"><b>Duration</b></label>
    			<input type="text" name="uduration" size="1">
    			<label for="ucat"><b>Category</b></label>
    			<input type="text" name="ucategory" size="1">
				<label for="uttn"><b>Total Amount $</b></label>
    			<input type="text" name="utotal_amount" size="1">
    			<label for="ucrtn"><b>Current Amount $</b></label>
    			<input type="text" name="ucurrent_amount" size="1">
    			<label for="cpubr"><b>Publisher</b></label>
    			<input type="text" name="upublisher" size="1">
    			<label for="uip"><b>index</b></label>
    			<input type="text" name="iproject" size="1" required>
			</div>
		</form>
		<form name="dproject" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="dprojectbtn" name="dproject">Delete</button>
				<label for="dpid"><b>Project ID</b></label>
    			<input type="text" name="dproject_id" size="1" required>
			</div>
		</form>
		<li><h2>Funding Records</h2></li>
		<table>
			<th>Index</th>
			<th>User ID</th>
			<th>Project ID</th>
			<th>Fund Time</th>
			<th>Amount</th>
			<?php
				$count = 0;
				while ($row = pg_fetch_assoc($fundsx)) {
					$count = $count + 1;
			?>
				<tr>
					<td align='center' width='200'> <?php echo $count ?> </td>
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
    			<input type="text" name="cfuser_id" size="1" required>
    			<label for="cfpid"><b>Project ID</b></label>
    			<input type="text" name="cfproject_id" size="1" required>
    			<label for="cfdor"><b>Funding Time</b></label>
    			<input type="text" name="cfund_time" required>
				<label for="cfam"><b>Amount $</b></label>
    			<input type="text" name="camount" size="1" required>
			</div>
		</form>
		<form name="ufund" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="ufundbtn" name="ufund">Modify</button>
				<label for="ufuid"><b>User ID</b></label>
    			<input type="text" name="ufuser_id" size="1">
    			<label for="ufpid"><b>Project ID</b></label>
    			<input type="text" name="ufproject_id" size="1">
    			<label for="ufdor"><b>Funding Time</b></label>
    			<input type="text" name="ufund_time">
				<label for="ufam"><b>Amount $</b></label>
    			<input type="text" name="uamount" size="1">
    			<label for="uif"><b>index</b></label>
    			<input type="text" name="ifund" size="1" required>
			</div>
		</form>
		<form name="dfund" action="admin.php" method="POST">
			<div class="container">
				<button type="submit" class="dfundbtn" name="dfund">Delete</button>
				<label for="dfuid"><b>User ID</b></label>
    			<input type="text" name="dfuser_id" size="1" required>
    			<label for="dfpid"><b>Project ID</b></label>
    			<input type="text" name="dfproject_id" size="1" required>
    			<label for="dfdor"><b>Funding Time</b></label>
    			<input type="text" name="dfund_time" required>
			</div>
		</form>
	</ul>
	<?php
		if ($effect == -1) ;
		else if (!$effect) echo "Operation failed!";
		else echo "Operation succeeded!";
	?>
</body>
</html>