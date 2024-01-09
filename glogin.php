<?php


$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$dbname = "abashon"; /* Database name */

$con = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}



if (isset($_SESSION['login_id']) && isset($_SESSION['uname'])) {
    header('Location: ownerpage.php');
    exit;
}

// https://github.com/googleapis/google-api-php-client
// Install Composer
//Inside project folder open command line and install the client files
require 'glogin/vendor/autoload.php';


// Creating new google client instance
$client = new Google_Client();

// Enter your Client ID
$client->setClientId('115798904241-7mcr4uie2mt6mpc9f3krllv9pb6uaf8k.apps.googleusercontent.com');
// Enter your Client Secrect
$client->setClientSecret('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');
// Enter the Redirect URL
$client->setRedirectUri('http://localhost/AbashonTest/glogin_intrmdt.php');

// Adding those scopes which we want to get (email & profile Information)
$client->addScope("email");
$client->addScope("profile");


if (isset($_GET['code'])) :

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {

        $client->setAccessToken($token['access_token']);

        // getting profile information
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        // Storing data into database
        $id = mysqli_real_escape_string($con, $google_account_info->id);
        $full_name = mysqli_real_escape_string($con, trim($google_account_info->name));
        $email = mysqli_real_escape_string($con, $google_account_info->email);
        //$profile_pic = mysqli_real_escape_string($db_connection, $google_account_info->picture);

        // checking user already exists or not
        $get_user = mysqli_query($con, "SELECT `google_id` FROM `users` WHERE `user_email`='$email' and  `google_id`='$id'");
        if (mysqli_num_rows($get_user) > 0) {

            $_SESSION['login_id'] = $id;
            $_SESSION['mail'] = $email;
            $_SESSION['uname'] = $_SESSION['mail'];

            header('Location: ownerpage.php');
            exit;
        } else {

            // if user not exists we will insert the user
            $insert = mysqli_query($con, "INSERT INTO `users`(`google_id`,`name`,`user_email`) VALUES('$id','$full_name','$email')");

            if ($insert) {
                $_SESSION['login_id'] = $id;
                $_SESSION['mail'] = $email;

                header('Location: glogin_intrmdt.php');
                exit;
            } else {
                echo "Sign up failed!(Something went wrong).";
            }
        }
    } else {
        header('Location: signup.php');
        exit;
    }

else :
    // Google Login Url = $client->createAuthUrl(); 
?>



<?php endif; ?>