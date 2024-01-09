    <?php
    include "config.php";
    require "map.php";

    //Destroying cookie
    setcookie("house_id", "", time() - 36000000000);
    setcookie("user_email", "", time() - 36000000000);

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
    $query3 = "SELECT user_type from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
    }
    // if($u_type != "Tenant"){
    //     header('Location: ownerpage.php');
    // }

    $sql = "Select house_id, block, road, number, floor, bed_count, room_count, image_link, description, name from house where availability = 'Yes'";
    $result = $con->query($sql);


    $query = "select name from users where user_email='" . $uname . "'";
    $result1 = mysqli_query($con, $query);

    $query2 = "SELECT user_id from users WHERE user_type='Tenant' AND user_email='" . $uname . "'";
    $result2 = mysqli_query($con, $query2);
    foreach ($result2 as $row2) {
        $u_id = $row2["user_id"];
    }


    $query5 = "SELECT house_id, ongoing from payment where tenant_id = '" . $u_id . "'";
    $result5 = mysqli_query($con, $query5);
    if (isset($result5)) {
        foreach ($result5 as $row5) {
            $tenant_id = $u_id;
            $house_id = $row5["house_id"];
            $ongoing = $row5["ongoing"];



            $query6 = "Select house_id, block, road, number, floor, bed_count from house where availability = 'No' and tenant_id = '$u_id'";
            $result6 = $con->query($query6);
        }
    }



    if (isset($ongoing) && $ongoing == "No") {
        $query7 = "Select house_id, block, road, number, floor, bed_count from house where house_id = '$house_id' and availability = 'Yes'";
        $result7 = $con->query($query7);
    }


    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>

        <link rel="stylesheet" href="css/tenanthome.css">
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
            <a href="tenanthome.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
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
                        <a href="tenanthome.php">Home</a><br>
                        <a href="#curbooked">Booked Homes</a><br>
                        <a href="<?php echo "user_edit.php?uid=$u_id"; ?>">Account Update</a><br>
                        <a href="<?php echo "user_delete.php?uid=$u_id"; ?>">Account Delete</a><br>
                        <!-- <a href="">Account info</a> -->
                    </div>
                </div>
            </div>
        </nav>
        <div class="topdiv">
            <div class="maindiv">
                <div class="upperdiv">
                    <div class="round1">
                        <h1 class="maintext">
                            <t style="color: orange;">Hey</t> <u><?php foreach ($result1 as $row) {
                                                                        printf("%s", $row["name"]);
                                                                    }; ?></u><br> this is your
                            <t style="color: orange;">user panel</t><br>
                            <t>As a Tenant</t>
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
                    <h1 style="font-weight: 150;">Our Available Homes In</h1>
                    <h1><u>Bashundhara</u></h1>
                </div>
            </div>
            <div class="slideshow-container">
    <?php



if ($result->num_rows > 0) {

    for ($i = 0; $i < 7; $i++) {
        $row = $result->fetch_assoc();
        if (isset($row) ){
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

<div class="curbooked" id="curbooked">
    <div class="bookedtextins">
<div class="bookedimage"></div>
<h1 class="bookedtext">Check out your<u>booked houses</u></h1>
</div>
</div>
<div class="bookedtable">
        <table style="min-height: 80px; border:2px #cc2828 solid;">
            <div align="center"><span style="font-size: 15pt">Currently Booked Houses</span></div>

            <?php



            if (isset($result6) && $result6->num_rows > 0) {

                for ($i = 0; $i < 4; $i++) {
                    $row6 = $result6->fetch_assoc();
                    if ((isset($row6))) {

                        //need to use backslash before " to bypass it, as it's double quote php
                        echo "<tr onClick=\"location.href='booking_end.php?hid=" . $row6["house_id"] . "'\"> &nbsp;";

                        echo "
                        <td>&nbsp;<t style='font-weight:900;'>Block </t>
                        " . $row6["block"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Road </t>
                        " . $row6["road"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>House </t>
                        " . $row6["number"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Floor </t>
                        " . $row6["floor"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Bed rooms </t>" . $row6["bed_count"] . "&nbsp;
                        </td>
                        </tr>";
                    }
                }
            } else {
                echo "<h1 style='font-family: Montserrat;'>No Houses Booked</h1>";
            }
            ?>


        </table>

        </div>

<div class="bookedtable">
        <table style="min-height: 80px; border:2px #cc2828 solid;">
            <div align="center"><span style="font-size: 15pt">Last Booked House</span></div>

            <?php

            if (isset($result7) && $result7->num_rows > 0) {

                for ($i = 0; $i < 4; $i++) {
                    $row7 = $result7->fetch_assoc();
                    if (isset($row7)) {

                        //need to use backslash before " to bypass it, as it's double quote php
                        echo "<tr onClick=\"location.href='indhouse.php?hid=" . $row7["house_id"] . "'\"> &nbsp;";

                        echo "
                        <td>&nbsp;<t style='font-weight:900;'>Block </t>
                        " . $row7["block"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Road </t>
                        " . $row7["road"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>House </t>
                        " . $row7["number"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Floor </t>
                        " . $row7["floor"] . "&nbsp;</td>&nbsp;<td>&nbsp;<t style='font-weight:900;'>Bed rooms </t>" . $row7["bed_count"] . "&nbsp;
                        </td>
                        </tr>";
                    }
                }
            } else {
                echo "<h1 style='font-family: Montserrat;'>No Info</h1>";
            }
            ?>


        </table>

        </div>




        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB05zfqieA-MHmYZ8L7gVVVvV_jmOwPCg&callback=initMap">
        </script>
          <script src="js/slideshow.js"></script>
        <script src="js/home.js"></script>
    </body>

    </html>