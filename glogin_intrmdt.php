  <?php
    //Intermediate page between Google login and user page
    //When user signs up using Google, this page will take some additional info from them
    include "config.php";
    include "glogin.php";

    //if session on
    if (isset($_SESSION['uname'])) {
        header('Location: ownerpage.php');
    }

    //Checks if user has a type at least
    $uname = $_SESSION['mail'];

    $query3 = "SELECT user_type from users WHERE user_email='" . $uname . "'";
    $result3 = mysqli_query($con, $query3);
    foreach ($result3 as $row3) {
        $u_type = $row3["user_type"];
    }

    if ($u_type == "Owner") {
        header('Location: ownerpage.php');
    }



    $gid = $_SESSION['login_id'];
    $uemail = $_SESSION['mail'];

    // define variables and set to empty values
    $emptErr = $typeErr = $uphoneErr = $ugenderErr = "";
    $utype = $ugender = $uphone = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $utype = mysqli_real_escape_string($con, $_POST['user']);
        $uphone = mysqli_real_escape_string($con, $_POST['phone']);
        $ugender = mysqli_real_escape_string($con, $_POST['gender']);

        $sql = "Select user_id from users where user_email='" . $uemail . "';";
        $result = mysqli_query($con, $sql);

        if (empty($utype) || empty($uphone) || empty($ugender)) {
            $emptErr = "All the fields are required";
        } elseif ($utype != "Tenant" && $utype != "Owner") {
            $typeErr = "Enter valid type";
        } elseif (!preg_match('@^\+?(88)?0?1[3456789][0-9]{8}\b@', $uphone)) {
            $uphoneErr = "Enter 11 digit Bangladeshi mobile number!";
        } elseif ($ugender != "Male" && $ugender != "Female" && $ugender != "Other") {
            $ugenderErr = "Select valid gender!";
        } else {



            $sql = "UPDATE users SET user_type = '" . $utype . "', gender= '" . $ugender . "', contact_no= '" . $uphone . "' where google_id = '" . $gid . "' ";

            $result = $con->query($sql);

            if ($result) {
                $_SESSION['uname'] = $_SESSION['mail'];
                header('Location: signup.php');
            } else {
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
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
                  <span style="font-size: 15px; color: red;"><?php echo $uphoneErr; ?></span>
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