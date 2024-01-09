<table id="search" class="">
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


  if (isset($_POST['selected_rent'])) {
    $result1 = $db->getRecordsWhere($_POST['selected_rent']);
  } elseif (isset($_POST['selected_block'])) {
    $result1 = $db->getRecordsWhereBlock($_POST['selected_block']);
  } elseif (isset($_POST['selected_fac'])) {
    $result1 = $db->getRecordsWhereFac($_POST['selected_fac']);
  }



  //Results after filtering
  while ($row1 = mysqli_fetch_array($result1)) {
    echo "<tr onClick=\"window.open('indhouse.php?hid=" . $row1["house_id"] . "')\">
		<td><img style=\"display: block; margin: auto;\" src='".$row1["image_link"]."' width=\"100px\"></td>
		<td>" . $row1['block'] . "</td>
		<td>" . $row1['number'] . "</td>
		<td>" . $row1['road'] . "</td>
		<td>" . $row1['floor'] . "</td>
					<td>" . $row1['room_count'] . "</td>
					<td>" . $row1['bed_count'] . "</td>
					<td>" . $row1['generator'] . "</td>
					<td>" . $row1['lift'] . "</td>
					<td>" . $row1['area'] . "</td>
		<td>" . $row1['rent'] . "</td>
	</tr>";
  }
  $db->closeCon();
  ?>
</table>

<!-- Sorting table -->
<!-- I added sorting method for both numeric and alphabetic data -->
<script>
  function sortTable(n, isNum) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("search");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
      //start by saying: no switching is done:
      switching = false;
      rows = table.rows;
      /*Loop through all table rows (except the
      first, which contains table headers):*/
      for (i = 1; i < (rows.length - 1); i++) {
        //start by saying there should be no switching:
        shouldSwitch = false;
        /*Get the two elements you want to compare,
        one from current row and one from the next:*/
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];

        //If the values of the column are numbers
        if (isNum) {
          if (dir == "asc") {
            if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          } else if (dir == "desc") {
            if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        } else {
          /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
          if (dir == "asc") {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          } else if (dir == "desc") {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        }
      }
      if (shouldSwitch) {
        /*If a switch has been marked, make the switch
        and mark that a switch has been done:*/
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        //Each time a switch is done, increase this count by 1:
        switchcount++;
      } else {
        /*If no switching has been done AND the direction is "asc",
        set the direction to "desc" and run the while loop again.*/
        if (switchcount == 0 && dir == "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
</script>