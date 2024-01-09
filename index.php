<?php
include "config.php";
require "map.php";

// Check user login or not
if (isset($_SESSION['uname'])) {
    header('Location: ownerpage.php');
}


$sql = "Select house_id, block, road, number, floor, room_count, availability, description, image_link, name from house where availability = 'Yes'";
$result = $con->query($sql);


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="stylesheet" href="css/home.css">
    <link rel="icon" href="images/abashon_logo.png">
    <title>Abashon</title>

    <script>
        var map;
        var marker;
        var infowindow;
        var icon = 'https://i.postimg.cc/kXwvD52c/house.png';
        var locations = <?php get_all_locations() ?>;

        function initMap() {
            var bashundhara = {
                lat: 23.8195825,
                lng: 90.4368458
            };
            infowindow = new google.maps.InfoWindow();
            map = new google.maps.Map(document.getElementById('map'), {
                center: bashundhara,
                zoom: 14,

            });


            var i;
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
                    icon: icon
                });

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {

                        //Concatenating house id
                        var link = "indhouse.php?hid=" + marker.house;

                        infowindow.setContent('House number: ' + marker.number +
                            '<br> Block: ' + marker.block +
                            '<br> Road: ' + marker.road +
                            '<br> Total area: ' + marker.area + 'sft' +
                            '<br><a href="' + link + '"><button>House Details</button></a>');
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
        <a href="index.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
        <div class="nav-right"><a href="signup.php"><button class="loginbutton">Sign in</button></a>
            <div class="hamburger">
                <div class="line1" id="line1"></div>
                <div class="line2" id="line2"></div>
                <div class="line3" id="line3"></div>
            </div>
            <div class="menu-overlay">
                <div class="nav-links">
                    <!-- <a href="">About Us</a><br> -->
                    <a href="#ourhomes">Our Homes</a><br>
                    <a href="signup.php">Become a Host</a><br>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="topdiv">
        <div class="maindiv">
            <div class="upperdiv">
                <div class="round1">
                    <h1 class="maintext">
                        <t style="color: orange;">Find</t> a suitable <br><u>home</u> for a<br>
                        <t style="color: orange;">comfortable stay</t>
                    </h1>
                </div>
                <div class="round2">

                </div>
            </div>
        </div>
        <div class="searchbox">
            <h1 class="textsearchbox">Look for a listing in your desired area</h1>
            <div class="inputsearch">
            </div><input type="button" onClick="location.href='searchresult.php'" class="searchbutton" name="but_submit" value="Click here to search" style="font-size: large; font-family:'Montserrat'; font-weight:bold;">
        </div>
    </div>

    <div class="ourhomes" id="ourhomes">
        <div class="homestitle">
            <div class="homestitletop" id="map">
            </div>
            <div class="homestitlebottom">
                <h1 style="font-weight: 150;">Our Homes in</h1>
                <h1><u>Bashundhara</u></h1>
            </div>
        </div>
        <div class="slideshow-container">
            <?php



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
    <script src="js/home.js"></script>
    <script src="js/slideshow.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

</body>

</html>