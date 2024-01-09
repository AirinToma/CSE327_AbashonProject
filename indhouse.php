<?php
include "config.php";

// Check user login or not
// if (!isset($_SESSION['uname'])) {
//     header('Location: index.php');
// }

// logout
// if (isset($_POST['but_logout'])) {
//     session_destroy();
//     header('Location: index.php');
// }

$hid = $_GET['hid'];
$avaErr = "";



$sql = "Select block, road, number, floor, room_count, availability, washroom_count, bed_count, generator, lift, area, description, image_link, owner_id, rent, owner_name, image2, image3, name from house where house_id='$hid'";

foreach ($con->query($sql) as $row) {
  $oid = "$row[owner_id]";
  $availability =  "$row[availability]";
}

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
  //To use variable from outside function
  global $hid;

  $sqldata = mysqli_query($con2, "select owner_id, lat, longi, block, road, number, area, image_link, rent as marker from house where house_id='$hid'");

  $rows = array();
  while ($r = mysqli_fetch_assoc($sqldata)) {
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

  <link rel="stylesheet" href="css/indhouse.css">
  <link rel="icon" href="images/abashon_logo.png">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
        zoom: 13,

      });


      var i;
      for (i = 0; i < locations.length; i++) {


        marker = new google.maps.Marker({
          //0th position owner id, 1st position latitude, 2nd position longitude
          //Check map.php for details
          lat: locations[i][1],
          longi: locations[i][2],
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),
          map: map,
          house: locations[i][5],
          block: locations[i][3],
          road: locations[i][4],
          area: locations[i][6],

          icon: icon,
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {

            infowindow.setContent('House number: ' + marker.house +
              '<br> Block: ' + marker.block +
              '<br> Road: ' + marker.road +
              '<br> Total area: ' + marker.area + 'sft');
            map.setZoom(17);
            map.setCenter(marker.getPosition());
            infowindow.open(map, marker);
            marker.setAnimation(google.maps.Animation.BOUNCE);
          }
        })(marker, i));
      }
    }
  </script>
  <title>Abashon</title>
</head>

<body>
  <br>
  <br>
  <nav class="nav" id="nav">
    <a href="index.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
    <div class="nav-right">
      <div class="hamburger">
        <div class="line1" id="line1"></div>
        <div class="line2" id="line2"></div>
        <div class="line3" id="line3"></div>
      </div>
      <div class="menu-overlay">
        <div class="nav-links">
          <a href="index.php">Home</a><br>
          <a href="contact.php">Contact Us</a><br>
        </div>
      </div>
    </div>
  </nav>
  
  <br>

  <?php
  foreach ($con->query($sql) as $row) {
    $img = "$row[image_link]";
    $img2 = "$row[image2]";
    $img3 = "$row[image3]";
  }
  ?>
  <div class='slideshow-container'>
    <?php foreach ($con->query($sql) as $row) {
      echo "
    <!-- Full-width images with number and caption text -->
      <div class='mySlides fade'>
        <img src='$img' style='width:100%; height:inherit;border-radius:inherit;'>
      </div>
    
      <div class='mySlides fade'>
        <img src='$img2' style='width:100%; height:inherit;border-radius:inherit;'>
      </div>
    
      <div class='mySlides fade'>
        
        <img src='$img3' style='width:100%; height:inherit; border-radius:inherit;'>
      </div>
    </div>
    <br>
    
    <!-- The dots/circles -->
    <div style='text-align:center'>
      <span class='dot' onclick='currentSlide(1)'></span>
      <span class='dot' onclick='currentSlide(2)'></span>
      <span class='dot' onclick='currentSlide(3)'></span>
    </div>";
    }
    ?>

    <div class="bottompart">
      <div class="canvasleft">
        <!-- Contains the address, facilities and the booking button -->
        <div class="infobox">
            <div class="topinfo">
            <?php foreach ($con->query($sql) as $row) {
              echo "<h1 style='font-family:Montserrat;font-size:1.3rem;'>$row[name]</h1>
              <hr class='hl'>";
              echo "<img src='1583576661537355876.svg' style='height: 15px; width:20px'> <t style='font-family:Montserrat;font-size:1rem;'>$row[description]</t><br>
              <hr class='hl'>";
              echo "<t style='font-family:Montserrat;font-size:1rem;'>Owned by <a style='color:blue; text-decoration:bolder;' href='ownerinfo.php?oid=$oid'>$row[owner_name]</a></t><br>
              <hr class='hl'>";
            } ?>
            </div>
            <div class="middleinfo">
          <div class="abox">
            <h3>Address</h3>
            <?php foreach ($con->query($sql) as $row) {
              echo "Block: </u> $row[block] <br>";
              echo "Road: $row[road] <br>";
              echo "House number: $row[number]";
            } ?>
          </div>
          <hr class="vertical"> 
          <div class="abox">
            <h3>Facilities</h3>
            <?php foreach ($con->query($sql) as $row) {
                echo "Bedrooms: </u> $row[bed_count] <br>";
                echo "Washroom: </u> $row[washroom_count] <br>";
                echo "Area: $row[area]sqft<br>";
              echo "Generator: </u> $row[generator] <br>";
              echo "Elevator: $row[lift]";
            } ?>
          </div>
            </div>
            <div class="bottominfo">
                <div class="indiv">
                    <h3>Rent</h3>
                    <?php foreach ($con->query($sql) as $row) {
                echo "৳$row[rent]/=<br>";
            } ?>
                </div>
                <hr class="vertical">
                <div class="indiv">
                <?php foreach ($con->query($sql) as $row) {
                if($row['availability']=='Yes'){
                    echo "<h1 style='color:Green; font-size:larger;'>Available</h1><br>";
                }
                else{
                    echo "<h1 style='color:Red;font-size:larger;'>Not Available</h1><br>";
                }
            } ?>
                </div>
            </div>
        </div>
        <button onClick="location.href='payment.php?hid=<?php echo $hid; ?>'" type="submit" name="book" value="Book now" style="font-family: Montserrat; font-weight:bold;" class="booking">Book Now</button>
      </div>
      <div class="canvasright">
        <div class="map" id="map"></div>
        <h1 style="font-family: Montserrat; font-weight:bold; font-size:1.4rem; color:white;">Location</h1>
      </div>
    </div>

    <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB05zfqieA-MHmYZ8L7gVVVvV_jmOwPCg&callback=initMap">
    </script>
    <script src="js/slideshow.js"></script>
    <script src="js/home.js"></script>
</body>

</html>