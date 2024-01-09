<?php
include "config.php";


//if session on
if (isset($_SESSION['uname'])) {
    header('Location: ownerpage.php');
}

$passErr = $verErr = $code = $status = "";


if (isset($_POST['but_submit'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['txt_pwd']);
    $code = mysqli_real_escape_string($con, $_POST['code']);

    if ($email != "" && $password != "" && $code != "") {

        $sql_query = "select count(*) as cntUser from users where user_email='" . $email . "' and password='" . md5($password) . "' and acc_confirm='" . $code . "'";
        $result = mysqli_query($con, $sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if ($count > 0) {
            $status = 1;

            //update portion
            try {
                $sql = $con->prepare('
         UPDATE
            users 
         SET
            confirm_status = ?
         WHERE 
            user_email = ?;
        ');
                $sql->bind_param('is', $status, $email);
                $sql->execute();
                header('Location: ownerpage.php');
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
            //update portion

            $_SESSION['uname'] = $email;

            header('Location: ownerpage.php');
        } else {
            $verErr = "Wrong Verification Code!";
            $passErr = "Email/password invalid!";
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
</head>

<body>

    <div class="main">
        <nav class="nav" id="nav">
            <a href="index.php"><img class="nav-logo" id="nav-logo" src="images/abashon_logo.png" alt="logo"></a>
            <div class="nav-right">
                <!-- <button class="loginbutton">Sign in</button> -->
                <div class="hamburger">
                    <div class="line1" id="line1"></div>
                    <div class="line2" id="line2"></div>
                    <div class="line3" id="line3"></div>
                </div>
                <div class="menu-overlay">
                    <div class="nav-links">
                        <a href="index.php">Home</a><br>
                        <!-- <a href="">About Us</a><br> -->
                        <!-- <a href="">Our Homes</a><br> -->
                        <!-- <a href="">Become a Host</a><br> -->
                        <a href="contact.php">Contact Us</a>
                    </div>
                </div>
            </div>
        </nav>



        <div class="content">
            <div class="content_left">
                <h2 style="font-family: 'Montserrat'; color: #ffbf5f;">Check Your Email for
                    <br><span>Verification code</span>
                    <br> Or <a href="resend_verify.php">Click here</a> to request again!
                </h2>

            </div>
            <div class="form">
                <h2>Email Confirmation</h2>
                <form action="" method="post">
                    <input type="email" name="email" placeholder="Enter Email Here">

                    <input type="password" name="txt_pwd" placeholder="Enter Password Here">
                    <span style="font-size: 15px; color: yellow;"><?php echo $passErr; ?></span>
                    <input type="text" name="code" placeholder="Enter Verification Code">
                    <span style="font-size: 15px; color: yellow;"><?php echo $verErr; ?></span>

                    <input type="submit" value="Login" name="but_submit" class="btnn">
                </form>



            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="js/home.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js">
    </script>

</body>

</html>