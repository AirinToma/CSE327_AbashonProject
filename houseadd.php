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
    $query3 = "SELECT user_type, name from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
        $owner_name = $row3["name"];
    }
    if($u_type != "Owner"){
        header('Location: tenanthome.php');
    }



    // define variables and set to empty values
    $emptErr = $blockErr = $roadErr = $houseErr = $floorErr = $nbedsErr = $nwashErr = $genErr = $elevatorErr = $imgErr = $areaErr = $rentErr= $housedesErr = "";
    $block = $housedes = $road = $house = $floor = $room = $nbeds = $nwash = $gen = $elevator = $img = $area = $locErr = $rent = $img2= $img3= "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $block = mysqli_real_escape_string($con, strtoupper($_POST['block']));
        $housename= mysqli_real_escape_string($con, $_POST['name']);
        $housedes= mysqli_real_escape_string($con, $_POST['des']);
        $road = mysqli_real_escape_string($con, $_POST['road']);
        $house = mysqli_real_escape_string($con, $_POST['house']);
        $floor = mysqli_real_escape_string($con, $_POST['floor']);
        $room = mysqli_real_escape_string($con, $_POST['room_count']);
        $nbeds = mysqli_real_escape_string($con, $_POST['nbeds']);
        $nwash = mysqli_real_escape_string($con, $_POST['nwash']);
        $gen = mysqli_real_escape_string($con, $_POST['yesg']);
        $elevator = mysqli_real_escape_string($con, $_POST['yese']);
        $img = mysqli_real_escape_string($con, $_POST['img']);
        $img2 = mysqli_real_escape_string($con, $_POST['img2']);
        $img3 = mysqli_real_escape_string($con, $_POST['img3']);
        $area = mysqli_real_escape_string($con, $_POST['area']);
        $latitude = mysqli_real_escape_string($con, $_POST['lati']);
        $longitude = mysqli_real_escape_string($con, $_POST['longi']);
        $rent = mysqli_real_escape_string($con, $_POST['rent']);


        //Getting id of the logged in user
        $query = "select user_id from users where user_email='" . $uname . "'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_row($result);
        $id = $row[0];


        if (empty($block) || empty($housename) || empty($housename) || empty($road) || empty($house) || empty($floor) || empty($room) || empty($nbeds) || empty($nwash) || empty($gen) || empty($elevator) || empty($img) || empty($area) || empty($latitude) || empty($longitude) || empty($rent)) {
            $emptErr = "All the fields are required";
        } elseif (!preg_match('@^[A-N]{1}@', $block)) {
            $blockErr = "Enter correct block, between A-N";
        } elseif (!is_numeric($road)) {
            $roadErr = "Road number should be numeric";
        } elseif (!is_numeric($house)) {
            $houseErr = "House number should be numeric";
        } elseif (strlen($housedes) > 30) {
            $housedesErr = "Description must be less than 30 characters";
        } elseif (!is_numeric($floor)) {
            $floorErr = "Floor number should be numeric";
        } elseif (!is_numeric($room)) {
            $roomErr = "Room count should be numeric";
        } elseif (!is_numeric($nbeds)) {
            $nbedsErr = "House count should be numeric";
        } elseif (!is_numeric($nwash)) {
            $nwashErr = "Washroom count should be numeric";
        } elseif ($gen != "Yes" && $gen != "No") {
            $genErr = "Put a valid value";
        } elseif ($elevator != "Yes" && $elevator != "No") {
            $elevatorErr = "Put a valid value";
        } elseif (!preg_match('@(https?:\/\/.*\.(?:png|jpg))@', $img) || !preg_match('@(https?:\/\/.*\.(?:png|jpg))@', $img2) || !preg_match('@(https?:\/\/.*\.(?:png|jpg))@', $img3) ) {
            $imgErr = "Enter correct image link!";
        } elseif (!is_numeric($area)) {
            $areaErr = "Area should be a numeric value";
        } elseif (!is_numeric($rent)) {
            $rentErr = "Rent amount should be numeric";
        } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
            $locErr = "Enter a numeric value for location";
        } else {


            //This method prevents sql injection
            $stmt = $con->prepare("INSERT INTO house (owner_id, block, road, number, floor, room_count, washroom_count, bed_count, generator, lift, image_link, area, lat, longi, rent, owner_name, image2, image3, name, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isiiiiiisssiddisssss", $id, $block, $road, $house, $floor, $room, $nwash, $nbeds, $gen, $elevator, $img, $area, $latitude, $longitude, $rent, $owner_name, $img2, $img3, $housename, $housedes);

            if ($stmt->execute()) {
                header('Location: ownerpage.php');
            } else {
                echo "ERROR: Could not able to execute $sql_query. " . mysqli_error($con);
            }

            mysqli_close($con);
        }
    }

    ?>



    <!DOCTYPE html>
    <html>

    <head>
        <link rel="icon" href="images/abashon_logo.png">
        <title>Add House</title>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB05zfqieA-MHmYZ8L7gVVVvV_jmOwPCg"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="js/map.js"></script>
        <link rel="stylesheet" href="css/map.css">
        <link rel="stylesheet" href="css/houseadd.css">
</head>

<body>
    <nav class="nav" id="nav">
        <a href="index.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
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
                    <a href="">About Us</a><br>
                    <a href="">Our Homes</a><br>
                    <a href="">Become a Host</a><br>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="canvas">

        <div class="upperdiv"></div>
        <!-- <img src="images/house.jpg" class="searchpic"alt=""> -->

        <form action="" class="form" method="post">
            <h1>Add House</h1>
            <table>

                <span style="font-size: 15px; color: red;"><?php echo $emptErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $blockErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $roadErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $houseErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $nbedsErr; ?></span> 
                <span style="font-size: 15px; color: red;"><?php echo $nwashErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $genErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $elevatorErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $imgErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $areaErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $housedesErr; ?></span>



                <tr class="tr">
                    <td>
                        <h2 class="form_boxes">Block (A-N)</h2>
                    </td>
                    <td>
                        <input type="text" class="input_text" placeholder="Block number" name="block">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">House Name</h2>
                    </td>
                    <td>
                        <input type="text" class="input_text" placeholder="House Name" name="name">
                    </td>
                </tr>
                <tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Road</h2>
                    </td>
                    <td>
                        <input type="text" class="input_text" placeholder="Road number" name="road">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">House Number</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="House number" name="house">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Floor</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Floor number" name="floor">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Number of rooms</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Total room count" name="room_count">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Number of beds</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Number of bedrooms" name="nbeds">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Number of Washrooms</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Total washrooms" name="nwash">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Area in square feets</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Area of house in sft" name="area">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Rent (in BDT)</h2>
                    </td>
                    <td>
                        <input type="number" class="input_text" placeholder="Rent in BDT" name="rent">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Generator Available</h2>
                    </td>
                    <td>
                        <h2 class="form_boxes">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No</h2>

                        <h2 class="form_boxes"><input type="radio" class="input_text" name="yesg" id="gen" value="Yes" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="yesg" id="gen" value="No"></h2>

                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Elevator Available</h2>
                    </td>
                    <td>
                        <h2 class="form_boxes">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No</h2>

                        <h2 class="form_boxes"><input type="radio" class="input_text" name="yese" value="Yes" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="yese" value="No"></h2>

                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Description</h2>
                    </td>
                    <td>
                        <input type="text" class="input_text" placeholder="Less than 30 characters" name="des">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Add 1st image url: (<a href="https://postimages.org/" target="_blank">here</a>)</h2>
                    </td>
                    <td>
                        <input type="url" class="input_text" placeholder="Direct image url" name="img">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Add 2nd image url: (<a href="https://postimages.org/" target="_blank">here</a>)</h2>
                    </td>
                    <td>
                        <input type="url" class="input_text" placeholder="Direct image url" name="img2">
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2 class="form_boxes">Add 3rd image url: (<a href="https://postimages.org/" target="_blank">here</a>)</h2>
                    </td>
                    <td>
                        <input type="url" class="input_text" placeholder="Direct image url" name="img3">
                    </td>
                </tr>

            </table>

            <!-- Map -->
            <p>Put the marker on your house location</p>

            <input type="button" class="btn  pull-right map-btn" value="Click here for Your Current Location" onclick="javascript:showlocation()" />

            <div id="map-canvas" style="height: 500px; width: 700px;" ></div>

            <input type="text" id="current_latitude" name="lati" placeholder="Latitude" />
            <input type="text" id="current_longitude" name="longi" placeholder="Longitude" />

            <input type="submit" class="submit_button" name="but_submit" value="Enter">
        </form>
    </div>




</body>

</html>