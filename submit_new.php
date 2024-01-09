<?php
include "config.php";


$emptErr = $passErr = $matchErr = "";


if(isset($_POST['password']) ) //&& $_POST['key'] && $_POST['reset']
{
  $email=$_GET['email'];
  $pass=$_POST['password'];
  $repass=$_POST['repassword'];


  if(empty($email) || empty($pass) || empty($repass))
  {
    $emptErr = "All the fields are required. Go back.";
  } elseif (strlen($pass)<5 || strlen($pass)>7 || !preg_match('@[A-Z]@', $pass) || !preg_match('@[a-z]@', $pass) || !preg_match('@[0-9]@', $pass) || preg_match('@[^\w]@', $pass)) {
    $passErr = "Password cannot be less than 5 and more than 7 characters, it must contain one upper, one lower case alphabet and a digit, no special characters allowed";
} elseif ($pass != $repass) {
    $matchErr = "Passwords don't match!";
} else{

  $select=mysqli_query($con, "update users set password='".md5($pass)."' where user_email='".$email."'");
  echo "Password Changed Successfully! <br>";
  echo "Now go back to <a href='http://localhost/AbashonTest/signup.php'>Login Page</a>";

}

}else{
  echo "Invalid Request. Now go back to <a href='http://localhost/AbashonTest/signup.php'>Login Page</a> and try again!";
}
?>


<html>
  <body>
    <p><?php echo $emptErr ?></p>
    <p><?php echo $passErr ?></p>
    <p><?php echo $matchErr ?></p>
  </body>
</html>