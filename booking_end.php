    <?php
    include "config.php";


    // Check user login or not
    if (!isset($_SESSION['uname'])) {
        header('Location: index.php');
    }

    // logout
    if (isset($_POST['but_logout'])) {
        session_destroy();
        header('Location: index.php');
    }

    $uname = $_SESSION['uname'];


    //Checks if user is a owner, as this page is only for owners
    $query3 = "SELECT user_type, user_id, name from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
        $id = $row3["user_id"];
        $name = $row3["name"];
    }

    if($u_type != "Tenant"){
        header('Location: ownerpage.php');
    }

//Get house id from URL
    $hid = $_GET['hid'];


//Getting house info of the logged in user
    $query = "SELECT ongoing, tenant_id from payment WHERE tenant_id='" . $id . "'";
    $result1 = mysqli_query($con, $query);
    foreach ($result1 as $row1) {
        $ongoing = $row1["ongoing"];
    }



    // define variables and set to empty values
    $ongoingErr = $emptErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        $ongoingPost = mysqli_real_escape_string($con, $_POST['ongoing']);



        if (empty($ongoingPost)) {
            $emptErr = "All the fields are required";
        } elseif ($ongoingPost != "No") {
            $ongoingErr = "Put a valid value";
        } else {


            try{
                $sql = $con->prepare('
                   UPDATE
                   payment 
                   SET 
                   ongoing = ?
                   WHERE 
                   tenant_id     = ?
                   AND
                   house_id    = ?;
                   ');

                if($ongoingPost == "No"){
                    $availability = "Yes";

                    $sql->bind_param('sii', $ongoingPost, $id, $hid);
                    $sql->execute();


                    try{
                        $zero = 0;
                        $nobody = "No one right now!";
                        $sql2 = $con->prepare('
                           UPDATE
                           house 
                           SET 
                           availability = ?,
                           tenant_id = ?,
                           tenant_name = ?
                           WHERE 
                           house_id    = ?;
                           ');


                        $sql2->bind_param('sisi', $availability, $zero, $nobody, $hid);
                        $sql2->execute();
                        header('Location: tenanthome.php');
                    }catch (Exception $e){
                        echo $e->getMessage();
                        exit;
                    }
                }else{
                    $availability = "No";
                }


    //header('Location: tenanthome.php');
            }catch (Exception $e){
                echo $e->getMessage();
                exit;
            }




        }
    }





    ?>



    <!DOCTYPE html>
    <html>

    <head>
        <link rel="icon" href="images/abashon_logo.png">
        <title>Booking update</title>
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
            <h1>Hello <?php echo $name?>,</h1>
            <h3>Press the Button at the Bottom to Cancel Booking for This House</h3>
            <table>

                <span style="font-size: 15px; color: red;"><?php echo $emptErr; ?></span>
                <span style="font-size: 15px; color: red;"><?php echo $ongoingErr; ?></span>

                
                <tr>
                    <td>
                        <h2 class="form_boxes">Cancel booking from next month?</h2>
                    </td>
                    <td>
                        <h2 class="form_boxes">Yes</h2>

                        <h2 class="form_boxes"><input type="radio" class="input_text" name="ongoing" id="gen" value="No" checked></h2>
                        <p>Is it booked by you?: <?php echo $ongoing; ?></p>

                    </td>
                </tr>



            </table>

            <input type="submit" class="submit_button" name="but_submit" value="Update">
        </form>

    </div>




</body>

</html>