  <?php
    include "config.php";

    //if session on
    if (isset($_SESSION['uname'])) {
        header('Location: ownerpage.php');
    }



    // define variables and set to empty values
    $emptErr = $typeErr = $unameErr = $uemailErr = $upassErr = $uphoneErr = $ugenderErr = $code = "";
    $utype = $uname = $uemail = $ugender = $uemail = $uphone = $password = $repassword = "";


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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $utype = mysqli_real_escape_string($con, $_POST['user']);
        $uname = mysqli_real_escape_string($con, $_POST['name']);
        $uemail = mysqli_real_escape_string($con, $_POST['email']);
        $upassword =  mysqli_real_escape_string($con, $_POST['password']);
        $repassword = mysqli_real_escape_string($con, $_POST['repassword']);
        $uphone = mysqli_real_escape_string($con, $_POST['phone']);
        $ugender = mysqli_real_escape_string($con, $_POST['gender']);

        $sql = "Select user_id from users where user_email='" . $uemail . "';";
        $result = mysqli_query($con, $sql);

        if (empty($utype) || empty($uname) || empty($uemail) || empty($upassword) || empty($uphone) || empty($ugender)) {
            $emptErr = "All the fields are required";
        } elseif ($utype != "Tenant" && $utype != "Owner") {
            $typeErr = "Enter valid type";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $uname)) {
            $unameErr = "Only letters and white space allowed for name";
        } elseif (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } elseif (strlen($upassword) < 5 || strlen($upassword) > 7 || !preg_match('@[A-Z]@', $upassword) || !preg_match('@[a-z]@', $upassword) || !preg_match('@[0-9]@', $upassword) || preg_match('@[^\w]@', $upassword)) {
            $upassErr = "Password cannot be less than 5 and more than 7 characters, it must contain one upper, one lower case alphabet and a digit, no special characters allowed";
        } elseif ($upassword != $repassword) {
            $upassErr = "Passwords don't match!";
        } elseif (!is_numeric($uphone) || !preg_match('@^\+?(88)?0?1[3456789][0-9]{8}\b@', $uphone)) {
            $uphoneErr = "Enter 11 digit Bangladeshi mobile number!";
        } elseif ($ugender != "Male" && $ugender != "Female" && $ugender != "Other") {
            $ugenderErr = "Select valid gender!";
        } elseif ($result->num_rows > 0) {
            $uemailErr = "Email already in the system!";
        } else {


            //This method prevents sql injection
            $stmt = $con->prepare("INSERT INTO users (user_type, name, password, gender, contact_no, user_email, acc_confirm) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $utype, $uname, md5($upassword), $ugender, $uphone, $uemail, $code);

            if ($stmt->execute()) {

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
                $mail->AddAddress('' . $uemail . '', '' . $uname . '');
                $mail->Subject  =  'Account Verfication';
                $mail->IsHTML(true);
                $mail->Body    = 'This is your email verification code: ' . $code . '';
                if ($mail->Send()) {
                    header('Location: acc_verify.php');
                } else {
                    echo "Mail Error - >" . $mail->ErrorInfo;
                }
                //Send mail

            } else {
                echo "ERROR: Could not able to execute $sql_query. " . mysqli_error($con);
            }

            mysqli_close($con);
        }
    }


    ?>










  <!DOCTYPE html>
  <html>

  <head>
      <link rel="icon" href="images/abashon_logo.png">
      <title>Add House</title>
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

          <form action="" class="form" method="post">
              <table>
                  <h2>Sign Up Form</h2>
                  <span style="font-size: 15px; color: red;"><?php echo $emptErr; ?></span>
                  <span style="font-size: 15px; color: red;"><?php echo $typeErr; ?></span>
                  <span style="font-size: 15px; color: red;"><?php echo $unameErr; ?></span>
                  <span style="font-size: 15px; color: red;"><?php echo $uemailErr; ?></span>
                  <span style="font-size: 15px; color: red;"><?php echo $uphoneErr; ?></span> <span style="font-size: 15px; color: red;"><?php echo $upassErr; ?></span>
                  <span style="font-size: 15px; color: red;"><?php echo $ugenderErr; ?></span>
                  <tr>
                      <td>
                          <h2 class="form_boxes">User Type</h2>
                      </td>
                      <td>
                          <h2 class="form_boxes">Tenant&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Flat Owner</h2>

                          <h2 class="form_boxes"><input type="radio" class="input_text" name="user" value="Tenant" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" name="user" value="Owner"></h2>

                      </td>
                  </tr>


                  <tr class="tr">
                      <td>
                          <h2 class="form_boxes">Full Name</h2>
                      </td>
                      <td>
                          <input type="text" class="input_text" placeholder="Full name" name="name">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h2 class="form_boxes">Email</h2>
                      </td>
                      <td>
                          <input type="email" class="input_text" placeholder="Email" name="email">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h2 class="form_boxes">Mobile Number</h2>
                      </td>
                      <td>
                          <input type="tel" class="input_text" placeholder="11 digit Mobile number" name="phone">
                      </td>
                  </tr>
                  <tr>
                      <td>
                          <h2 class="form_boxes">Password</h2>
                      </td>
                      <td>
                          <input type="password" class="input_text" placeholder="Password" name="password">
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



                  <tr>
                      <td>
                          <h2 class="form_boxes">Gender</h2>
                      </td>
                      <td>
                          <h2 class="form_boxes">Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Female&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Other</h2>

                          <h2 class="form_boxes"><input type="radio" class="input_text" placeholder="" name="gender" id="gen" value="Male" checked>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="gender" id="gen" value="Female">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="input_text" placeholder="" name="gender" id="gen" value="Other"></h2>

                      </td>
                  </tr>

              </table>

              <input type="submit" class="submit_button" name="but_submit" value="Sign up"></button>
          </form>
      </div>




  </body>

  </html>