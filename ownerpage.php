    <?php
    include "config.php";

    // Check user login or not
    if (!isset($_SESSION['uname'])) {
        header('Location: index.php');
    }

    // logout
    if (isset($_POST['but_logout'])) {
        setcookie("house_id", "", time() - 36000000000);
        setcookie("user_email", "", time() - 36000000000);
        session_destroy();
        header('Location: index.php');
    }

    $uname = $_SESSION['uname'];


//Checks if user is a owner, as this page is only for owners
    $query3 = "SELECT user_type, user_id from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
    }
    if($u_type != "Owner"){
        header('Location: tenanthome.php');
    }



    $query = "select name from users where user_email='" . $uname . "'";
    $result1 = mysqli_query($con, $query);

    $query2 = "SELECT user_id from users WHERE user_type='Owner' AND user_email='" . $uname . "'";
    $result2 = mysqli_query($con, $query2);
    foreach ($result2 as $row2) {
        $u_id = $row2["user_id"];
    }

    

//Loading available houses
    function get_all_locations(){
//Including config.php page again won't work
        $host = "localhost"; /* Host name */
        $user = "root"; /* User */
        $password = ""; /* Password */
        $dbname = "abashon"; /* Database name */

        $con2 = mysqli_connect($host, $user, $password,$dbname);

// Check connection
        if (!$con2) {
           die("Connection failed: " . mysqli_connect_error());
       }
    //To use variable from outside function
       global $u_id;

       $sqldata = mysqli_query($con2,"
        select house_id, lat, longi, block, road, number, area, availability, tenant_name as marker from house where owner_id='$u_id'
        ");

       $rows = array();
       while($r = mysqli_fetch_assoc($sqldata)) {
        $rows[] = $r;

    }
    $indexed = array_map('array_values', $rows);
  //  storing the fetched result in array

    echo json_encode($indexed);
    if (!$rows) {
        return null;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="css/ownerhome.css">
    <link rel="icon" href="images/abashon_logo.png">
    <title>Abashon</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
      
        var map;
        var marker;
        var infowindow;
        var icon = 'https://i.postimg.cc/kXwvD52c/house.png';
        var locations = <?php get_all_locations() ?>;

        function initMap() {
            var bashundhara = {lat: 23.8195825, lng: 90.4368458};
            infowindow = new google.maps.InfoWindow();
            map = new google.maps.Map(document.getElementById('map'), {
                center: bashundhara,
                zoom: 14,

            });


            var i ;
            for (i = 0; i < locations.length; i++) {

                marker = new google.maps.Marker({
              //0th position house id, 1st position latitude, 2nd position longitude
              lat: locations[i][1],
              longi: locations[i][2],
              position: new google.maps.LatLng(locations[i][1], locations[i][2]),
              map: map,
              house: locations[i][0],
              block: locations[i][3], 
              road: locations[i][4], 
              number: locations[i][5], 
              area: locations[i][6],
              availability: locations[i][7],
              tenant: locations[i][8],
              icon : icon
          });

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {

                        var link = "house_edit.php?hid="+marker.house;
                        var del = "house_delete.php?hid="+marker.house;
                        
                        infowindow.setContent('House number: '+marker.number+
                         '<br> Block: '+marker.block+
                         '<br> Road: '+marker.road+
                         '<br> Total area: '+marker.area+'sft'+
                         '<br> Availability: '+marker.availability+
                         '<br> Tenant: '+marker.tenant+
                         '<br><a href="'+link+'"><button class="loginbutton">Edit House</button></a>'+
                         '<br><a href="'+del+'"><button>Delete House</button></a>');
                        map.setZoom(17);
                        map.setCenter(marker.getPosition());
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }

    </script>
</head>

<body>

    <nav class="nav" id="nav">
        <a href="ownerpage.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
        <div class="nav-right">
            <form method='post' action="">
                <input class="loginbutton" type="submit" value="Logout" name="but_logout">
            </form>
            <div class="hamburger">
                <div class="line1" id="line1"></div>
                <div class="line2" id="line2"></div>
                <div class="line3" id="line3"></div>
            </div>
            <div class="menu-overlay">
                <div class="nav-links">
                    <a href="#topdiv">Home</a><br>
                    <a href="#map">Your Homes</a><br>
                    <a href="houseadd.php">Add House</a><br>
                    <a href="<?php echo "user_edit.php?uid=$u_id"; ?>">Account Update</a><br>
                    <a href="<?php echo "user_delete.php?uid=$u_id"; ?>">Delete Account</a><br>
                    <!-- <a href="">Account info</a> -->
                </div>
            </div>
        </div>
    </nav>
    <div class="topdiv" id="topdiv">
        <div class="maindiv">
            <div class="upperdiv">
                <div class="round1">
                    <h1 class="maintext">
                        <t style="color: orange;">Hey</t> <u><?php foreach ($result1 as $row) {
                            printf("%s", $row["name"]);
                        }; ?></u><br> this is your
                        <t style="color: orange;">user panel</t>
                        <br> as an Owner
                    </h1>
                </div>
                <div class="round2">

                </div>
            </div>
        </div>
        <div class="searchbox" id="ownerbox">
            <h1><a href="houseadd.php" style="color: black;text-align: center;"><u>Add House</u></a></h1>
            </div>
    </div>

    <div class="yourhomes" id="yourhomes">
        <div class="homestitle">
            <div class="homestitletop" id="map">
            </div>
            <div class="homestitlebottom">
                <h1 style="font-weight: 150;">Your Homes in</h1>
                <h1><u>Bashundhara</u></h1>
            </div>
        </div>
        <div class="slideshow-container" >
            
                <?php

                $sql = "SELECT house_id, block, road, number, floor, room_count, availability, image_link, description, name from house where owner_id='$u_id'";
    $result = $con->query($sql);

                

    if ($result->num_rows > 0) {

        for ($i = 0; $i < 7; $i++) {
            $row = $result->fetch_assoc();
            if (isset($row["availability"]) == "Yes") {
                $img = "$row[image_link]";
                //need to use backslash before " to bypass it, as it's double quote php
                echo
               "<div class='mySlides fade' onClick=\"location.href='indhouse.php?hid=" . $row["house_id"] . "'\">
                <div class='text'>
                <div class='uppertextdiv' style='background-image:url($img);'>
                </div>
                <div class='middletextdiv'><h2>$row[name]</h2><br>$row[description]</div>
                <div class='bottomtextdiv'>
                <div class='bottomslidediv'>Block
                    " . $row["block"] . "<br>Road 
                    " . $row["road"] . "<br>House 
                    " . $row["number"] . "
                    </div>
                    <hr class='solid'>
                    <div class='bottomslidediv'>Floor
                    " . $row["floor"] . "<br>Bed rooms
                    " . $row["room_count"] . "<br>
                    </div>
                    </div>
                    </div>
                    </div>";

    
            }
        }
    } else {
        echo "<h1 style='font-family: Montserrat;'>No Result</h1>";
    }
    ?>

<!-- Next and previous buttons -->
<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
<a class="next" onclick="plusSlides(1)">&#10095;</a>
    
        </div>

    </div>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB05zfqieA-MHmYZ8L7gVVVvV_jmOwPCg&callback=initMap">
    </script>
    <script async src="js/slideshow.js"></script>
    <script src="js/home.js"></script>
</body>

</html>