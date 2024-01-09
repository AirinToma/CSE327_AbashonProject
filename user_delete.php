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

    if(!isset($_GET['uid'])){
    header('Location: index.php');
}
//Get user id from URL
$uid = $_GET['uid'];


    //Getting user info
    $query3 = "SELECT user_type, user_id, name from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
        $id = $row3["user_id"];
        $name = $row3["name"];
    }




 if (isset($_POST['del_submit'])) {

if($u_type == "Owner"){
    try{
        $del_query = "DELETE FROM users WHERE user_id = '".$uid."' ";
        $del_result = mysqli_query($con, $del_query);
        $del_query_house = "DELETE FROM house WHERE owner_id = '".$uid."' ";
        $del_result_house = mysqli_query($con, $del_query_house);
        session_destroy();
        header('Location: ownerpage.php');
    }catch (Exception $e){
        echo $e->getMessage();
        exit;
    }
}

if($u_type == "Tenant"){
    try{
        $update_query = "UPDATE house SET tenant_id = 0  WHERE tenant_id = '".$uid."' ";
        $update_result = mysqli_query($con, $update_query);
        $del_query = "DELETE FROM users WHERE user_id = '".$uid."' ";
        $del_result = mysqli_query($con, $del_query);
        session_destroy();
        header('Location: ownerpage.php');
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
    background-color: #f3115b;
    border-radius: 5px;
    font-family: "Raleway";
    z-index: 5;
    font-size: 1.15em;
    font-weight: 600;
    color: white;
    box-shadow: 0%;
    border-color: transparent;
}

.loginbutton:hover {
    cursor: pointer;
    background-color: orange;
    color: black;
}
#delbutton{
    width: 300px;
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


        <form method="post" onsubmit="return confirm(' Hello, <?php echo $name?>, Really Want to Delete your account and all the info related to it? This will be an irreversible snap of Thanos!!');">
            <input type="submit" name="del_submit" value="Delete Account and all info" class="loginbutton" id="delbutton">
        </form>
    </div>




</body>

</html>