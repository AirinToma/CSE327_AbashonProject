<?php
include "config.php";

$hid = $_GET['hid'];

//Cookies storing house_id and user_email for 18000 seconds
setcookie("house_id", $hid, time() + (18000));


//Check user login or not
if (!isset($_SESSION['uname'])) {
    header('Location: index.php');
}


// logout
// if (isset($_POST['but_logout'])) {
//     session_destroy();
//     header('Location: index.php');
// }

$uname = $_SESSION['uname'];
setcookie("user_email", $uname, time() + (18000));

//Checks if user is a owner, as this page is only for owners

$u_type = $cusid = $name = $phone = "";




$query = "SELECT user_type, name, contact_no, user_id from users WHERE user_email='" . $uname . "'";
$result = mysqli_query($con, $query);
foreach ($result as $row) {
    $u_type = $row["user_type"];
    $cusid = $row["user_id"];
    $name = $row["name"];
    $phone = $row["contact_no"];
}
if ($u_type == "Owner") {
    header('Location: ownerpage.php');
} elseif ($u_type != "Tenant") {
    header('Location: signup.php');
}



//Get house info
$block = $number = $rent = $block = $road = $address = "";

$query1 = "SELECT block, road, number, rent, owner_id, availability from house WHERE house_id='" . $hid . "'";
$result1 = mysqli_query($con, $query1);
foreach ($result1 as $row1) {
    $block = $row1["block"];
    $number = $row1["number"];
    $rent = $row1["rent"];
    $road = $row1["road"];
    $availability = $row1["availability"];
}

if ($availability == "No") {
    header('Location: signup.php');
}

$address = "Block number: $block<br> Road number: $road<br> House number: $number";


error_reporting(0);
date_default_timezone_set('Asia/Dhaka');
//Generate Unique Transaction ID
function rand_string($length)
{
    $str = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    $size = strlen($chars);
    for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0, $size - 1)];
    }

    return $str;
}
$cur_random_value = rand_string(10);

?>


<html>
<title>Secured Payment Gateway</title>

<head>
    <style>
        .maindiv {
            height: 100vh;
            width: 100vw;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .formdiv {
            height: 60%;
            background-color: white;
            border-radius: 20px;
            width: 60%;
            display: flex;
            align-items: center;
            font-family: 'Montserrat';

        }

        .paynow {
            width: 100px;
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

        .paynow:hover {
            cursor: pointer;
            background-color: orange;
            color: black;
        }
    </style>
</head>

<body>
    <div class="maindiv">

        <form class="formdiv" style='margin:0 auto; text-align:center;' action="https://sandbox.aamarpay.com/index.php" method="post" name="form1">
            <table border="0" cellpadding="4" cellspacing="2" align="center" style="border-collapse:collapse;">
                <input type="hidden" name="store_id" value="aamarpay">
                <input type="hidden" name="signature_key" value="28c78bb1f45112f5d40b956fe104645a">

                </td>
                </tr>
                <tr>
                    <td>Merchant Transaction ID: *</td>
                    <td><input type="hidden" name="tran_id" value="WEP-<?php echo "$cur_random_value"; ?>">ABASHON-<?php echo "$cur_random_value"; ?></td>
                </tr>
                <tr>
                    <td>Pay Amount: </td>
                    <td><input type="hidden" name="amount" value="<?php echo $rent; ?>"><?php echo $rent; ?> Taka</td>
                </tr>

                <tr>
                    <td>Currency: </td>
                    <td><input type="hidden" name="currency" value="BDT">BDT</td>
                </tr>

                <tr>
                    <td>Customer Name: </td>
                    <td><input type="hidden" name="cus_name" value="<?php echo $name; ?>"><?php echo $name; ?></td>
                </tr>
                <tr>
                    <td>Customer Email Address: </td>
                    <td><input type="hidden" name="cus_email" value="<?php echo $uname; ?>"><?php echo $uname; ?></td>
                </tr>
                <tr>
                    <td>Customer Address Line: </td>
                    <td><input type="text" name="cus_add1" value="Dhaka"></td>
                </tr>

                <tr>
                    <td>Customer Phone: </td>
                    <td><input type="hidden" name="cus_phone" value="<?php echo $phone; ?>"><?php echo $phone; ?></td>
                </tr>

                <tr>
                    <td>Product Description: </td>
                    <td><input type="hidden" name="desc" value="<?php echo $address; ?>"><?php echo $address; ?></td>
                </tr>
                <input type="hidden" name="success_url" value="http://localhost/AbashonTest/success.php">
                <input type="hidden" name="fail_url" value="http://localhost/AbashonTest/fail.php">
                <input type="hidden" name="cancel_url" value="http://localhost/AbashonTest/fail.php">



                <tr>
                    <td><input type="submit" class='paynow' value="Pay Now" name="pay"><br /></td>
                </tr>
            </table>
        </form>
    </div>







</body>

</html>