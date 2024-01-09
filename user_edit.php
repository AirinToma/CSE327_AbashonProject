  <?php
  include "config.php";

//if session on
  if(!isset($_SESSION['uname'])){
    header('Location: index.php');
}

$user_mail = $_SESSION['uname'];

if(!isset($_GET['uid'])){
    header('Location: index.php');
}
//Get user id from URL
$uid = $_GET['uid'];




//Getting house info of the logged in user
        $query = "SELECT user_id, name, contact_no from users WHERE user_email='" . $user_mail . "'";
        $result1 = mysqli_query($con, $query);
        foreach ($result1 as $row1) {
        $n = $row1["name"];
        $c = $row1["contact_no"];
        $i = $row1["user_id"];
    }


// define variables and set to empty values
$emptErr = $unameErr = $uemailErr = $upassErr = $uphoneErr = "";
$uname = $uemail = $uemail = $uphone = $password = $repassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && $uid == $i){

  $uname = mysqli_real_escape_string($con,$_POST['name']);
  $uemail = mysqli_real_escape_string($con,$_POST['email']);
  $upassword = mysqli_real_escape_string($con,$_POST['password']);
  $repassword = mysqli_real_escape_string($con,$_POST['repassword']);
  $uphone = mysqli_real_escape_string($con,$_POST['phone']);

  $sql="Select user_id from users where user_email='".$uemail."';";
  $result = mysqli_query($con,$sql);

  if (empty($uname) || empty($uemail) || empty($upassword) || empty($uphone)) {
    $emptErr = "All the fields are required";
}elseif (!preg_match("/^[a-zA-Z-' ]*$/",$uname)) {
    $unameErr = "Only letters and white space allowed for name";
}elseif (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
}elseif (strlen($upassword)<5 || strlen($upassword)>7 || !preg_match('@[A-Z]@', $upassword) || !preg_match('@[a-z]@', $upassword) || !preg_match('@[0-9]@', $upassword) || preg_match('@[^\w]@', $upassword)) {
    $upassErr = "Password cannot be less than 5 and more than 7 characters, it must contain one upper, one lower case alphabet and a digit, no special characters allowed";
}elseif ($upassword != $repassword) {
    $upassErr = "Passwords don't match!";
}elseif (!preg_match('@^\+?(88)?0?1[3456789][0-9]{8}\b@', $uphone)) {
    $uphoneErr = "Enter 11 digit Bangladeshi mobile number!";
}elseif ($result-> num_rows>0 && $uemail != $user_mail) {
    $uemailErr = "Email already in the system!";
}else{


    //update portion
    try{
    $sql = $con->prepare('
     UPDATE
        users 
     SET
        name     = ?, 
        user_email     = ?, 
        password    = ?, 
        contact_no = ?
     WHERE 
        user_id     = ?;
    ');
    $sql->bind_param('ssssi', $uname, $uemail, md5($upassword), $uphone, $uid);
    $sql->execute();
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
    <style>
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
    background-image: url("images/house2.jpg");
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
            <div class="hamburger">
                <div class="line1" id="line1"></div>
                <div class="line2" id="line2"></div>
                <div class="line3" id="line3"></div>
            </div>
            <div class="menu-overlay">
                <div class="nav-links">
                    <a href="">About Us</a><br>
                    <a href="">Our Homes</a><br>
                    <a href="signup.php">Become a Host</a><br>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="canvas">

        <div class="upperdiv"></div>

        <form action="" class="form" method="post"  onsubmit="return confirm('Are you sure?');">
            <table>
                <h2>Update Account Information</h2>
                <span style="font-size: 15px; color: red;"><?php echo $emptErr;?></span>
                <span style="font-size: 15px; color: red;"><?php echo $unameErr;?></span>
                <span style="font-size: 15px; color: red;"><?php echo $uemailErr;?></span>
                <span style="font-size: 15px; color: red;"><?php echo $uphoneErr;?></span>                <span style="font-size: 15px; color: red;"><?php echo $upassErr;?></span>



                    <tr class="tr">
                        <td>
                            <h2 class="form_boxes">Full Name</h2>
                        </td>
                        <td>
                            <input type="text" class="input_text" value="<?php echo $n?>" name="name">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Email</h2>
                        </td>
                        <td>
                            <input type="email" class="input_text" value="<?php echo $user_mail?>" name="email">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Mobile Number</h2>
                        </td>
                        <td>
                            <input type="tel" class="input_text" value="<?php echo $c?>" name="phone">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Re-enter Current Password</h2>
                        </td>
                        <td>
                            <input type="password" class="input_text" placeholder="Or a different one" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2 class="form_boxes">Confirm Password</h2>
                        </td>
                        <td>
                            <input type="password" class="input_text" placeholder="Confirm Password" name="repassword">
                        </td>
                    </tr>
                    
                    
                        
                    </table>

                    <input type="submit" class="submit_button" name="but_submit" value="Update Info"></button>
                </form>
            </div>




        </body>

        </html>