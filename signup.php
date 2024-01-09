<?php
include "config.php";
include "glogin.php";


//if session on
if (isset($_SESSION['uname'])) {
    header('Location: ownerpage.php');
}

$passErr = "";


if (isset($_POST['but_submit'])) {

    $uname = mysqli_real_escape_string($con, $_POST['txt_uname']);
    $password = mysqli_real_escape_string($con, $_POST['txt_pwd']);

    if ($uname != "" && $password != "") {

        $sql_query = "select count(*) as cntUser from users where user_email='" . $uname . "' and password='" . md5($password) . "' and confirm_status = '1'";
        $result = mysqli_query($con, $sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if ($count > 0) {

            $_SESSION['uname'] = $uname;

            //Cookies storing username for 6000 secs

            if (!empty($_POST["remember"])) {
                setcookie("member_login", $_POST["txt_uname"], time() + (6000));
            } else {
                if (isset($_COOKIE["member_login"])) {
                    setcookie("member_login", "");
                }
            }

            header('Location: ownerpage.php');
        } else {
            $passErr = "Account not verified or Email/Password invalid!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="css/signup.css">
    <link rel="icon" href="images/abashon_logo.png">
    <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule="" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
</head>

<body>

    <div class="main">
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
                        <a href="contact.php">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>



        <div class="content">
            <div class="content_left">
                <h1 style="font-family: 'Montserrat'; color: #ffbf5f;"><img src="images/abashon.png" height="250px"><br><span> is your virtual</span>
                    <br>helping hand
                </h1>

                <br>
                <br>
                <button class="cn"><a href="signup1.php">Sign Up</a>
                    </form></button>
            </div>
            <div class="form">
                <h2>Login</h2>
                <form action="" method="post">
                    <input class="inputdata" type="email" name="txt_uname" placeholder="Enter Email Here" value="<?php if (isset($_COOKIE["member_login"])) {
                                                                                                    echo $_COOKIE["member_login"];
                                                                                                } ?>">

                    <input type="password" name="txt_pwd" placeholder="Enter Password Here">
                    <span style="font-size: 15px; color: yellow;"><?php echo $passErr; ?></span>

                    <input type="submit" value="Login" name="but_submit" class="btnn">

                    <label>Remember me <input style="height: 15px; position: absolute; margin: 6px; left: 46px;" type="checkbox" name="remember" id="remember" <?php if (isset($_COOKIE["member_login"])) { ?> <?php } ?> /></label>

                </form>
                <p class="liw"><a href="resend_verify.php">Request Email Verification</a></p>
                <p class="liw"><a href="send_link.php">Reset Password</a></p>


                <p class="liw">Log in with</p>

                <div class="icons">
                    <a href="<?php echo $client->createAuthUrl(); ?>">
                        <ion-icon name="logo-google"></ion-icon>
                    </a>

                </div>

            </div>
        </div>
    </div>
    </div>
    </div>
    <script async src="js/home.js"></script>

</body>

</html>