<?php
//Need to do this: https://www.google.com/settings/security/lesssecureapps
include "config.php";

$fieldErr = $emailErr = "";
if(isset($_POST['submit_email']) && $_POST['email'] && $_POST['name'])
{
   $email = mysqli_real_escape_string($con,$_POST['email']);
   $name = mysqli_real_escape_string($con,$_POST['name']);

   if(empty($email) || empty($name)){
    $fieldErr = "All the fields are required";
   }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailErr = "Invalid email format";
}else{

  $select=mysqli_query($con,"select user_email, password from users where user_email='$email'");
  if(mysqli_num_rows($select)==1)
  {
    foreach ($select as $row) {
        $email=$row['user_email'];
      $pass=md5($row['password']);
    }

    $link="<a href='http://localhost/AbashonTest/reset_pass.php?key=".$email."&reset=".$pass."'>Click To Reset password</a>";
    require_once('PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();
    // enable SMTP authentication
    $mail->SMTPAuth = true;                  
    // GMAIL username
    $mail->Username = "abashon.cse482@gmail.com";
    // GMAIL password
    $mail->Password = "fnxbfzvelnhdhbvb"; //OG Abashon.2022
    $mail->SMTPSecure = "ssl";  
    // sets GMAIL as the SMTP server
    $mail->Host = "smtp.gmail.com";
    // set the SMTP port for the GMAIL server
    $mail->Port = "465";
    $mail->From='abashon.cse482@gmail.com';
    $mail->FromName='Abashon';
    $mail->AddAddress(''.$email.'', ''.$name.'');
    $mail->Subject  =  'Reset Password';
    $mail->IsHTML(true);
    $mail->Body    = 'Click On This Link to Reset Password: '.$link.'<br> Ignore if you did not request this.';
    if($mail->Send())
    {
      echo "Check Your Email and Click on the link sent to your email <br>";
      echo "Go back to <a href='http://localhost/AbashonTest/signup.php'>Login Page</a>";
    }
    else
    {
      echo "Mail Error - >".$mail->ErrorInfo;
    }
  }

}
	
}
?>
<html>
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
.formtop{
    width: 200px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-around;
    flex-direction: column;
    /* align-items: center; */
    gap: 10px;
}
#textbox{
height: 30px;
width: 200px;
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
  <body>
    <div class="maindiv">
    <form class="formdiv" method="post" action="send_link.php">
    <p>Enter Email Address To Send Password Link</p> 
    <div class="formtop">
      <p><?php echo $fieldErr ?></p>
      <input type="text" name="name" placeholder="Your Name" id="textbox">
      <input type="text" name="email" placeholder="Enter Your Email" id="textbox"><p><?php echo $emailErr ?></p>
      <input class="button1" type="submit" name="submit_email">
      </div>
    </form>
    </div>
  </body>
</html>