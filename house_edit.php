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
        $id = $row3["user_id"];
    }

    if ($u_type != "Owner") {
        header('Location: tenanthome.php');
    }

    //Get house id from URL
    $hid = $_GET['hid'];


    //Getting house info of the logged in user
    $query = "SELECT owner_id, block, road, number, floor, room_count, washroom_count, bed_count, generator, lift, image_link, area, lat, longi, availability, rent from house WHERE house_id='" . $hid . "'";
    $result1 = mysqli_query($con, $query);
    foreach ($result1 as $row1) {
        $oi = $row1["owner_id"];
        $b = $row1["block"];
        $r = $row1["road"];
        $n = $row1["number"];
        $f = $row1["floor"];
        $rc = $row1["room_count"];
        $wc = $row1["washroom_count"];
        $bc = $row1["bed_count"];
        $g = $row1["generator"];
        $l = $row1["lift"];
        $il = $row1["image_link"];
        $a = $row1["area"];
        $la = $row1["lat"];
        $lo = $row1["longi"];
        $av = $row1["availability"];
        $re = $row1["rent"];
    }


    // define variables and set to empty values
    $emptErr = $blockErr = $roadErr = $houseErr = $floorErr = $nbedsErr = $nwashErr = $genErr = $elevatorErr = $imgErr = $areaErr = $avErr = $ownerErr = $rentErr = "";
    $block = $road = $house = $floor = $room = $nbeds = $nwash = $gen = $elevator = $img = $availability = $area = $locErr = $rent = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $block = mysqli_real_escape_string($con, strtoupper($_POST['block']));
        $road = mysqli_real_escape_string($con, $_POST['road']);
        $house = mysqli_real_escape_string($con, $_POST['house']);
        $floor = mysqli_real_escape_string($con, $_POST['floor']);
        $room = mysqli_real_escape_string($con, $_POST['room_count']);
        $nbeds = mysqli_real_escape_string($con, $_POST['nbeds']);
        $nwash = mysqli_real_escape_string($con, $_POST['nwash']);
        $gen = mysqli_real_escape_string($con, $_POST['yesg']);
        $elevator = mysqli_real_escape_string($con, $_POST['yese']);
        $availability = mysqli_real_escape_string($con, $_POST['avyes']);
        $img = mysqli_real_escape_string($con, $_POST['img']);
        $area = mysqli_real_escape_string($con, $_POST['area']);
        $latitude = mysqli_real_escape_string($con, $_POST['lati']);
        $longitude = mysqli_real_escape_string($con, $_POST['longi']);
        $rent = mysqli_real_escape_string($con, $_POST['rent']);





        if (empty($block) || empty($road) || empty($house) || empty($floor) || empty($room) || empty($nbeds) || empty($nwash) || empty($gen) || empty($elevator) || empty($img) || empty($area) || empty($latitude) || empty($longitude) || empty($availability)) {
            $emptErr = "All the fields are required";
        } elseif (!preg_match('@^[A-N]{1}@', $block)) {
            $blockErr = "Enter correct block, between A-N";
        } elseif (!is_numeric($road)) {
            $roadErr = "Road number should be numeric";
        } elseif (!is_numeric($house)) {
            $houseErr = "House number should be numeric";
        } elseif (!is_numeric($floor)) {
            $floorErr = "Floor number should be numeric";
        } elseif (!is_numeric($room)) {
            $roomErr = "Room count should be numeric";
        } elseif (!is_numeric($nbeds)) {
            $nbedsErr = "House count should be numeric";
        } elseif (!is_numeric($nwash)) {
            $nwashErr = "Washroom count should be numeric";
        } elseif ($gen != "Yes" && $gen != "No") {
            $genErr = "Put a valid value for generator";
        } elseif ($elevator != "Yes" && $elevator != "No") {
            $elevatorErr = "Put a valid value for elevator";
        } elseif ($availability != "Yes" && $availability != "No") {
            $elevatorErr = "Put a valid value for availability";
        } elseif (!preg_match('@(https?:\/\/.*\.(?:png|jpg))@', $img)) {
            $imgErr = "Enter 11 digit Bangladeshi mobile number!";
        } elseif (!is_numeric($area)) {
            $areaErr = "Area should be a numeric value";
        } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
            $locErr = "Enter a numeric value for location";
        } elseif (!is_numeric($rent)) {
            $rentErr = "Rent amount should be numeric";
        } elseif ($id != $oi) {
            $ownerErr = "This is not your house";
        } else {


            //update portion

            try {
                $sql = $con->prepare('
     UPDATE
        house 
     SET
        block     = ?, 
        road     = ?, 
        number    = ?, 
        floor = ?,
        room_count     = ?, 
        washroom_count     = ?, 
        bed_count    = ?, 
        generator = ?,
        lift     = ?, 
        image_link     = ?, 
        area    = ?, 
        lat = ?,
        longi     = ?,
        availability = ?,
        rent = ?
     WHERE 
        house_id     = ?
        AND
        owner_id    = ?;
    ');
                $sql->bind_param('siiiiiisssiddsiii', $block, $road, $house, $floor, $room, $nwash, $nbeds, $gen, $elevator, $img, $area, $latitude, $longitude, $availability, $rent, $hid, $id);
                $sql->execute();
                header('Location: ownerpage.php');
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
            //update portion


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
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;900&display=swap");
            @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;900&display=swap");
            /*---------------------------------------
     NAV              
  -----------------------------------------*/

            .nav {
                min-width: 100vw;
                height: 15vh;
                padding: 12px 4%;
                color: rgba(0, 0, 0, 0.575);
                position: fixed;
                top: 0;
                left: 0;
                right: 10px;
                z-index: 3;
                display: flex;
                align-items: center;
                justify-content: space-between;
                transition: all ease 0.5s;
                background-color: rgba(0, 0, 0, 0.596);
            }

            .nav-right {
                display: flex;
                gap: 80px;
                justify-content: center;
                align-items: center;
            }

            .nav-logo {
                height: 130px;
                position: relative;
                z-index: 5;
            }

            .loginbutton {
                width: 100px;
                height: 50px;
                background-color: white;
                border-radius: 5px;
                font-family: "Raleway";
                z-index: 5;
                font-size: 1.15em;
                font-weight: 600;
                color: black;
                box-shadow: 0%;
                border-color: transparent;
            }

            .loginbutton:hover {
                cursor: pointer;
                background-color: #f3115b;
                color: white;
            }

            .hamburger {
                cursor: pointer;
                position: relative;
                z-index: 5;
            }

            .line1,
            .line2,
            .line3 {
                width: 25px;
                height: 2px;
                background-color: white;
                margin: 5px 0;
                transition: all ease-in-out 0.7s;
            }

            .line1.morph {
                transform: rotate(45deg) translate(0, 10px);
                background-color: white;
            }

            .line2.morph {
                opacity: 0;
            }

            .line3.morph {
                transform: rotate(-45deg) translate(0, -10px);
                background-color: white;
            }

            .menu-overlay {
                height: 100vh;
                min-height: 500px;
                width: 100vw;
                visibility: hidden;
                position: fixed;
                top: 0;
                left: 100vw;
                z-index: 4;
                background-color: rgba(0, 0, 0, 0.95);
                transition: all ease-in-out 0.4s;
            }

            .menu-overlay.open {
                visibility: visible;
                transform: translateX(-100%);
            }

            .menu-overlay a:hover {
                color: #f3115b;
            }

            .nav-links {
                position: absolute;
                top: 50%;
                left: 95%;
                transform: translate(-95%, -50%);
                text-align: right;
                font-family: "Montserrat", sans-serif;
                width: fit-content;
            }

            .nav-links a {
                text-transform: uppercase;
                font-size: 2.4rem;
                font-weight: 800;
                line-height: 45px;
            }

            .upperdiv {
                background-image: url("../images/house2.jpg");
                height: 40vh;
                width: 100vw;
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                display: flex;
                justify-content: space-around;
                flex-direction: row;
                margin-bottom: 30px;
            }

            h1,
            h2,
            h3,
            h4 {
                font-family: 'Monsterrat', sans-serif;
            }

            table {
                width: 50vw;
                color: black;

            }

            td,
            tr {
                /* border-bottom: 1px solid #ddd; */
                width: 50vw;
                border-top: 2px solid grey;
                font-family: 'Montserrat';
                font-weight: 600;
            }



            .canvas {
                display: flex;
                /* height: 100vh; */
                padding: 0px 20px;
                justify-content: center;
                align-items: center;
                /* background-image: url('https://images.pexels.com/photos/7130560/pexels-photo-7130560.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260'); */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                flex-direction: column;
            }

            .form {
                /* height: 50vh; */
                width: 60vw;
                padding: 15px 20px;
                /* border: 5px goldenrod solid; */
                background-color: rgba(250, 235, 215, 0.603);
                border-radius: 10px;
                color: #da0e29;
                box-shadow: #da0e29;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;

            }

            .ftop {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                margin-bottom: 30px;
            }

            .form_boxes {
                display: flex;
                align-items: center;
                /* text-align:  */
                /* margin-left: 20px; */
                font-family: 'Montserrat';
                font-weight: 600;
            }

            .input_text {
                height: 30px;
                margin-left: 10px;
                font-family: 'Montserrat';
                font-weight: 600;
            }


            .submit_button {
                height: 40px;
                width: 100px;
            }
        </style>



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

            <form action="" class="form" method="post" onsubmit="return confirm('Are you sure?');">
                <h1>Update House Info</h1>
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
                    <span style="font-size: 15px; color: red;"><?php echo $avErr; ?></span>
                    <span style="font-size: 15px; color: red;"><?php echo $ownerErr; ?></span>



                    <tr class="tr">
                        <td>
                            <h2 class="form_boxes">Block (A-N)</h2>
                        </td>
                        <td>
                            <input type="text" class="input_text" value="<?php echo $b; ?>" name="block">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Road</h2>
                        </td>
                        <td>
                            <input type="text" class="input_text" value="<?php echo $r; ?>" name="road">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">House Number</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $n; ?>" name="house">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Floor</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $f; ?>" name="floor">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Number of rooms</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $rc; ?>" name="room_count">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Number of beds</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $bc; ?>" name="nbeds">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Number of Washrooms</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $wc; ?>" name="nwash">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Area in square feets</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $a; ?>" name="area">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Rent (in BDT)</h2>
                        </td>
                        <td>
                            <input type="number" class="input_text" value="<?php echo $re; ?>" name="rent">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Generator Available</h2>
                        </td>
                        <td>
                            <h2 class="form_boxes">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No</h2>

                            <h2 class="form_boxes"><input type="radio" class="input_text" name="yesg" id="gen" value="Yes" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="yesg" id="gen" value="No"></h2>
                            <p>Current Value: <?php echo $g; ?></p>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Elevator Available</h2>
                        </td>
                        <td>
                            <h2 class="form_boxes">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No</h2>

                            <h2 class="form_boxes"><input type="radio" class="input_text" name="yese" value="Yes" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="yese" value="No"></h2>
                            <p>Current Value: <?php echo $l; ?></p>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Availablability</h2>
                        </td>
                        <td>
                            <h2 class="form_boxes">Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No</h2>

                            <h2 class="form_boxes"><input type="radio" class="input_text" name="avyes" value="Yes" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="avyes" value="No"></h2>
                            <p>Current Value: <?php echo $av; ?></p>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Add image url: (<a href="https://postimages.org/" target="_blank">here</a>)</h2>
                        </td>
                        <td>
                            <input type="url" class="input_text" value="<?php echo $il; ?>" name="img">
                        </td>
                    </tr>

                </table>

                <!-- Map -->
                <p>Re-adjust the marker and put it on your house location</p>

                <input type="button" class="btn  pull-right map-btn" value="Click here for Your Current Location" onclick="javascript:showlocation()" />

                <div id="map-canvas" style="height: 500px; width: 700px;"></div>

                <input type="text" id="current_latitude" name="lati" placeholder="Latitude" />
                <input type="text" id="current_longitude" name="longi" placeholder="Longitude" />

                <input type="submit" class="submit_button" name="but_submit" value="Update Info">
            </form>

        </div>




    </body>

    </html>