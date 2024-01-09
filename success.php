<?php


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include "config.php";


    $uname = $_COOKIE["user_email"];
    $house_id = $_COOKIE["house_id"];

    $_SESSION['uname'] = $uname;


    $name = $_POST['cus_name'];
    $paystatus = $_POST['pay_status'];
    $cust_email = $_POST['cus_email'];
    $txn_id = $_POST['pg_txnid'];
    $mer_txn = $_POST['mer_txnid'];
    $amount = $_POST['amount'];
    $time = $_POST['pay_time'];
    $availability = "No";

    //For pdf. Gotta print within 30 seconds
    setcookie("name", $name, time() + (30));
    setcookie("paystatus", $paystatus, time() + (30));
    setcookie("cust_email", $cust_email, time() + (30));
    setcookie("txn_id", $txn_id, time() + (30));
    setcookie("amount", $amount, time() + (30));
    setcookie("time", $time, time() + (30));



    //you can get all parameter from post request
    //print_r($_POST);

    //House info
    $query1 = "SELECT owner_id from house WHERE house_id='" . $house_id . "'";
    $result1 = mysqli_query($con, $query1);
    foreach ($result1 as $row1) {
        $owner_id = $row1["owner_id"];
    }



    //If the received email is of the current user's who's logged in
    if ($cust_email == $uname) {

        //Get necessary info
        $query = "SELECT user_id, name from users WHERE user_email='" . $cust_email . "'";
        $result = mysqli_query($con, $query);
        foreach ($result as $row) {
            $tenant_id = $row["user_id"];
            $tenant_name = $row["name"];
        }


        //Updata payment
        $stmt = $con->prepare("INSERT INTO payment (house_id, tenant_id, owner_id, txn_id, amount, time, mer_txn) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisiss", $house_id, $tenant_id, $owner_id, $txn_id, $amount, $time, $mer_txn);

        if (!$stmt->execute()) {
            echo "ERROR: Could not able to execute $sql_query. " . mysqli_error($con);
        }

        //update portion
        try {
            $sql = $con->prepare('
     UPDATE
        house 
     SET 
        availability = ?,
        tenant_id = ?,
        tenant_name = ?
     WHERE 
        house_id     = ?;
    ');
            $sql->bind_param('sisi', $availability, $tenant_id, $tenant_name, $house_id);
            $sql->execute();



            //Send Email
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
            $mail->Password = "fnxbfzvelnhdhbvb"; //OG Abashon.2022
            $mail->SMTPSecure = "ssl";
            // sets GMAIL as the SMTP server
            $mail->Host = "smtp.gmail.com";
            // set the SMTP port for the GMAIL server
            $mail->Port = "465";
            $mail->From = 'abashon.cse482@gmail.com';
            $mail->FromName = 'Abashon';
            //Receiver mail
            $mail->AddAddress('' . $cust_email . '', '' . $name . '');
            $mail->Subject  =  'Booking confirmation';
            $mail->IsHTML(true);
            $mail->Body    = '<table border="0" cellpadding="4" cellspacing="2" align="center" style="border-collapse:collapse;">
                   
                    </td></tr>
                    <tr><td>Hello ' . $name . ',</td><td></td></tr>
                    <tr><td>Your Payment Was: </td><td><span style="color: green;">' . $paystatus . '</span></td></tr>
                    <tr><td>You Paid: </td><td>' . $amount . ' Taka</td></tr>
                    <tr><td>Transaction ID: </td><td>' . $txn_id . '</td></tr>
                    <tr><td>Payment Time: </td><td>' . $time . '</td></tr></table><br><div><span style="font-size: 10pt">Regards<br>Team Abashon</span></div>';
            if ($mail->Send()) {
                $notif = '<span  style="color: red; font-size: 15pt;">An email has been sent to you confirming this purchase.</span>';
            } else {
                echo "Mail Error - >" . $mail->ErrorInfo;
            }
            //Send mail



        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }


        mysqli_close($con);
    } else {
        header('Location: tenanthome.php');
    }
}


?>


<html>
<title>Successful Payment</title>

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

        .tablediv {
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

        .paynow {
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

        .paynow:hover {
            cursor: pointer;
            background-color: orange;
            color: black;
        }
    </style>
</head>

<body>
    <div class="maindiv">
        <div class="tablediv">
            <table border="0" cellpadding="4" cellspacing="2" align="center" style="border-collapse:collapse;">

                </td>
                </tr>
                <tr>
                    <td>Hello <?php echo $name; ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Your Payment Was: </td>
                    <td><?php echo "$paystatus"; ?></td>
                </tr>
                <tr>
                    <td>You Paid: </td>
                    <td><?php echo $amount; ?> Taka</td>
                </tr>
                <tr>
                    <td>Transaction ID: </td>
                    <td><?php echo $txn_id; ?></td>
                </tr>
                <tr>
                    <td>Payment Time: </td>
                    <td><?php echo $time; ?></td>
                </tr>
                <tr>
                    <td><input onClick="location.href='tenanthome.php'" type="submit" class='paynow' value="Go back to your user page" name="pay"><br /></td>
                </tr>
                <tr>
                    <td><input onClick="window.open('pdf_memo.php','_blank')" type="submit" class='paynow' value="Generate PDF" name="pdf"><br /></td>
                </tr>
            </table>

            <div align="center"><span style="font-size: 15pt;font-weight:bold;"><?php echo $notif; ?></span></div>
        </div>

    </div>




</body>

</html>