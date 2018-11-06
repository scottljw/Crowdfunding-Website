<!DOCTYPE html>
<html>
<head>
    <title>Crowdfunding: Browsing Project</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>li {list-style: none;}</style>
</head>
<body>
	<h2>Project List</h2>
	<form name="display" action="index.php" method="GET">
		<button type="submit">Return to Homepage</button>
	</form>
	<?php
		// Connect to the database. Please change the password in the following line accordingly
		$db = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=000000");
		$funded = pg_query($db, "SELECT project_id, title, category, current_amount FROM publish_projects WHERE current_amount >= total_amount");
		$unfunded = pg_query($db, "SELECT project_id, title, category, total_amount, total_amount - current_amount AS shortage FROM publish_projects WHERE current_amount < total_amount ORDER BY shortage DESC");
		$num_funded = pg_fetch_result(pg_query($db, "SELECT COUNT(*) AS COUNT FROM publish_projects WHERE current_amount >= total_amount"), 0, 0);
		$num_unfunded = pg_fetch_result(pg_query($db, "SELECT COUNT(*) AS COUNT FROM publish_projects WHERE current_amount < total_amount"), 0, 0);
		$average = pg_fetch_result(pg_query($db, "SELECT ROUND(AVG (total_amount - current_amount),0) FROM publish_projects WHERE current_amount < total_amount"), 0, 0);
	?>
	<h3>Funded Projects</h3>
	<p><?php echo $num_funded?> projects are funded.</p>
	<br/>
	<table>
		<th>Project ID</th>
		<th>Title</th>
		<th>Category</th>
		<th>Final Amount</th>
		<?php
			while($row=pg_fetch_assoc($funded)){
				echo "<tr>";
				echo "<td align='center' width='200'>#" . $row['project_id'] . "</td>";
				echo "<td align='center' width='200'>" . $row['title'] . "</td>";
				echo "<td align='center' width='200'>" . $row['category'] . "</td>";
				echo "<td align='center' width='200'>$" . $row['current_amount'] . "</td>";
				echo "</tr>";
			}
		?>
	</table>
	<h3>Projects seeking fund</h3>
	<p><?php echo $num_unfunded?> projects are unfunded.</p>
	<p>Average money shorted: $<?php echo $average?> projects are unfunded.</p>
	<br/>
	<table>
		<th>Project ID</th>
		<th>Title</th>
		<th>Category</th>
		<th>Total Amount</th>
		<th>Shortage</th>
		<?php
			while($row=pg_fetch_assoc($unfunded)){
				echo "<tr>";
				echo "<td align='center' width='200'>#" . $row['project_id'] . "</td>";
				echo "<td align='center' width='200'>" . $row['title'] . "</td>";
				echo "<td align='center' width='200'>" . $row['category'] . "</td>";
				echo "<td align='center' width='200'>$" . $row['total_amount'] . "</td>";
				echo "<td align='center' width='200'>$" . $row['shortage'] . "</td>";
				echo "</tr>";
			}
		?>
	</table>
 </body>
 </html>
