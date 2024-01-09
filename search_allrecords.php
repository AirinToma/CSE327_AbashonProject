<table class="" id="all">
	<tr>
		<th>Image</th>
		<th onclick="sortTable(1, false)">Block</th>
		<th onclick="sortTable(2, true)">House Number</th>
		<th onclick="sortTable(3, true)">Road</th>
		<th onclick="sortTable(4, true)">Floor</th>
		<th onclick="sortTable(5, true)">Room</th>
		<th onclick="sortTable(6, true)">Bed Count</th>
		<th onclick="sortTable(7, false)">Generator</th>
		<th onclick="sortTable(8, false)">Lift</th>
		<th onclick="sortTable(9, true)">Area</th>
		<th onclick="sortTable(10, true)">Rent</th>
	</tr>
	<?php
	require('search_config.php');
	$db = new db;
	$result = $db->getRecords();
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr onClick=\"window.open('indhouse.php?hid=" . $row["house_id"] . "')\">
					<td><img style=\"display: block; margin: auto;\" src='".$row["image_link"]."' width=\"100px\"></td>
					<td>" . $row['block'] . "</td>
					<td>" . $row['number'] . "</td>
					<td>" . $row['road'] . "</td>
					<td>" . $row['floor'] . "</td>
					<td>" . $row['room_count'] . "</td>
					<td>" . $row['bed_count'] . "</td>
					<td>" . $row['generator'] . "</td>
					<td>" . $row['lift'] . "</td>
					<td>" . $row['area'] . "</td>
					<td>" . $row['rent'] . "</td>
				</tr>";
	}
	$db->closeCon();
	?>
</table>