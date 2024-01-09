<?php
//Loading available houses
function get_all_locations()
{
    //Including config.php page again won't work
    $host = "localhost"; /* Host name */
    $user = "root"; /* User */
    $password = ""; /* Password */
    $dbname = "abashon"; /* Database name */

    $con2 = mysqli_connect($host, $user, $password, $dbname);

    // Check connection
    if (!$con2) {
        die("Connection failed: " . mysqli_connect_error());
    }


    $sqldata = mysqli_query($con2, "
    select house_id, lat, longi, block, road, number, area as marker from house where availability = 'Yes'
    ");

    //rows array stores data like [house_id] => 128 [lat] => 23.815499407447728
    $rows = array();
    while ($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;
    }
    //Array map stores rows like:  [0] => 128 [1] => 23.815499407447728
    $indexed = array_map('array_values', $rows);
    //  storing the fetched result in array

//json encode shows array map like: "128","23.815499407447728"
    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}
