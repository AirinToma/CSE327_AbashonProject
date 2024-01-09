<?php
include "config.php";


//if session on
if (isset($_SESSION['uname'])) {
    header('Location: ownerpage.php');
}

$email = $emailErr = $code = $emptErr = $verified = $error = "";


//Generates random 5 digit verification code
function generateRandomString($length = 5)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$code = generateRandomString();
//End of code generation section


if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $name = mysqli_real_escape_string($con, $_POST['name']);

    if (empty($name) || empty($email)) {
        $emptErr = "All the fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        //Checking if already confirmed
        $query = "Select confirm_status from users where user_email='" . $email . "';";
        $result = mysqli_query($con, $query);
        if (isset($result)) {
            foreach ($result as $row) {
                $status = $row["confirm_status"];
            }

            if ($status == 0) {
                //update portion
                try {
                    $sql = $con->prepare('
                         UPDATE
                            users 
                         SET
                            acc_confirm = ?
                         WHERE 
                            user_email = ?;
                        ');
                    $sql->bind_param('ss', $code, $email);
                    if ($sql->execute()) {
                        //If execution successful Send Email with verification code
                        require_once('PHPMailer/PHPMailerAutoload.php');
                        $notif = "";
                        $mail = new PHPMailer();
                        $mail->CharSet =  "utf-8";
                        $mail->IsSMTP();
                        // enable SMTP authentication
                        $mail->SMTPAuth = true;
                        // GMAIL username
                        $mail->Username = "abashon.cse482@gmail.com";
                        // GMAIL password
                        $mail->Password = "Abashon.2022";
                        $mail->SMTPSecure = "ssl";
                        // sets GMAIL as the SMTP server
                        $mail->Host = "smtp.gmail.com";
                        // set the SMTP port for the GMAIL server
                        $mail->Port = "465";
                        $mail->From = 'abashon.cse482@gmail.com';
                        $mail->FromName = 'Abashon';
                        //Receiver mail
                        $mail->AddAddress('' . $email . '', '' . $name . '');
                        $mail->Subject  =  'Account Verfication';
                        $mail->IsHTML(true);
                        $mail->Body    = 'This is your email verification code: ' . $code . '';
                        if ($mail->Send()) {
                            header('Location: acc_verify.php');
                        } else {
                            echo "Mail Error - >" . $mail->ErrorInfo;
                        }
                        //Send mail

                    }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    exit;
                }
                //update portion
            } else {
                $verified = "The account is already verified. Login from our login page.";
            }
        } else {
            $error = "Your account is already verified/You don't have any account with us!";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification Code</title>
    <style>
        html{
      overflow: hidden;
    }
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
</head>

<body>

    <div text-align:center class="maindiv">
        <form method="POST" class="formdiv">
            <span style="font-size: 15px; color: red;"><?php echo $emptErr; ?></span>
            <span style="font-size: 15px; color: red;"><?php echo $verified; ?></span>
            <span style="font-size: 15px; color: red;"><?php echo $error; ?></span>
           <div class="formtop">
               <label for="name">Name</label>
            <input type="text" name="name" placeholder="Enter Your Name" id="textbox">
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Enter Your Email Address" id="textbox">
            </div>
            <span style="font-size: 15px; color: red;"><?php echo $emailErr; ?></span>
            <input type="submit" name="submit" value="Resend Verfication Code" class="button1">
        </form>
    </div>

</body>

</html>