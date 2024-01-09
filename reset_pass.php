<?php
include "config.php";
$err = "";
if($_GET['key'] && $_GET['reset'])
{
   $email=$_GET['key'];
  $pass=$_GET['reset'];

  $select=mysqli_query($con,"select user_email, password from users where user_email='$email' and md5(password)='$pass'");
  if(mysqli_num_rows($select)==1)
  {
    ?>
    <div text-align:center class="maindiv">
    <form method="post" action="submit_new.php?email=<?php echo $email;?> " class="formdiv">
    <p>Enter New password</p>
    <input type="password" name='password' id="textbox">
    <p>Re-type password</p>
    <input type="password" name='repassword' id="textbox">
    <input type="submit" name="submit_password" class="button1">
    </form>
    </div>
    <?php
  } else{
    $err = "Use the latest reset link sent by us. Or go back to <a href='http://localhost/AbashonTest/signup.php'>Login Page</a> and try again!";
  } 
}
  
  

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification Code</title>
    <style>

        .maindiv{
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
   
}
.formdiv{
    height: 60%;
    background-color: white;
    border-radius: 20px;
    width: 60%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Montserrat';
    flex-direction: column;
    
}
#textbox{
height: 30px;
width: 200px;
margin-bottom: 10px;
}
.button1{
    /* width: 100px; */
    height: 30px;
    background-color: #f3115b;
    border-radius: 5px;
    font-family: "Raleway";
    z-index: 5;
    font-size: 1em;
    font-weight: 600;
    color: white;
    box-shadow: 0%;
    border-color: transparent;
}

.button1:hover{
    cursor: pointer;
    background-color: grey;
    color: white;
}
    </style>
</head>
  <body>
    <p><?php echo $err; ?></p>
  </body>
</html>

