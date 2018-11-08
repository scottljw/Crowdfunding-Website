<!DOCTYPE html>
<html>
<head>
	<title>Crowdfunding: Searching Project</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style>li {list-style: none;}</style>
</head>
<body>
	<h3>Search Your Interested Projects</h3>
	<form action="fund.php" method="POST">
		<input type="text" placeholder="Enter Project ID" name="project" required>
		<button type="submit" value="Send">Make Donation</button>
	</form>
	<br/>
	<div class="text-container">
		<form action="search.php" method="POST">
			<label for='input'>Search</label>
			<input type='text' placeholder="Search for something" name='input'>
			<select name='method'>
				<option name='ID' value="id">Search by ID</option>
				<option name='Category' value="cat">Search by Category</option>
				<option name='Title' value="tit">Search by Title</option>
				<option name='Description' value="desc">Search by Description</option>
			</select>
			<button type='submit' name='search' value='search'>Go</button>
		</form>
	</div>
	<br/>
	<?php
		session_start();
		// Connect to the database. Please change the password in the following line accordingly
		$db = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=000000");
		date_default_timezone_set("Asia/Singapore");
		$current_time = date("Y-m-d H:i:s");
		$key_word = $_POST[input];
		$upperkey = strtoupper($key_word);
		switch ($_POST[method]) {
		case "id":
			echo "You are searching projects by ID, ";
			$query = ("SELECT * FROM publish_projects WHERE UPPER(project_id) LIKE '%{$upperkey}%' ORDER BY project_id");
			$project = pg_query($db, $query);
			$num_searched = pg_fetch_array(pg_query($db, "SELECT COUNT(*) AS count FROM publish_projects WHERE UPPER(project_id) LIKE '%{$upperkey}%'"));
			echo $num_searched[0] . " projects are shown.";
			break;
		case "cat":
			echo "You are searching projects by category, ";
			$query = ("SELECT * FROM publish_projects WHERE UPPER(category) LIKE '%{$upperkey}%' ORDER BY project_id");
			$project = pg_query($db, $query);
			$num_searched = pg_fetch_array(pg_query($db, "SELECT COUNT(*) AS count FROM publish_projects WHERE UPPER(category) LIKE '%{$upperkey}%'"));
			echo $num_searched[0] . " projects are shown.";
			break;
		case "tit":
			echo "You are searching projects by title, ";
			$query = ("SELECT * FROM publish_projects WHERE UPPER(title) LIKE '%{$upperkey}%' ORDER BY project_id");
			$project = pg_query($db, $query);
			$num_searched = pg_fetch_array(pg_query($db, "SELECT COUNT(*) AS count FROM publish_projects WHERE UPPER(title) LIKE '%{$upperkey}%'"));
			echo $num_searched[0] . " projects are shown.";
			break;
		case "desc":
			echo "You are searching projects by description, ";
			$query = ("SELECT * FROM publish_projects WHERE UPPER(description) LIKE '%{$upperkey}%' ORDER BY project_id");
			$project = pg_query($db, $query);
			$num_searched = pg_fetch_array(pg_query($db, "SELECT COUNT(*) AS count FROM publish_projects WHERE UPPER(description) LIKE '%{$upperkey}%'"));
			echo $num_searched[0] . " projects are shown.";
			break;
		default:
			echo "No projects are searched";
		}
	?>
	<br/>
	<h2>Search Results</h2>
	<?php while ($row = pg_fetch_assoc($project)) { ?>
	<div class='projects clearfix'>
		<?php
			echo "#" . $row['project_id'] . "<br/>";
			echo $row['title'] . ", " . $row['category'] . " by " . $row['publisher'] . "<br/>";
			echo "Total Amount Required: $" . $row['total_amount'] . "<br/>";
			echo "Current Amount: $" . $row['current_amount'] . "<br/>";
		?>
		<form action="fund.php" method="POST">
			<button type="submit"> Donate </button>
			<input type="hidden" name="project" value="<?php $row['project_id']?>">
		</form>
	</div>
	<br/>
	<?php } ?>
</body>
</html>