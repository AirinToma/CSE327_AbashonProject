<?php

if($_SERVER['REQUEST_METHOD']=="POST"){

    $paystatus=$_POST['pay_status'];
    $amount=$_POST['amount'];
    $trxnid=$_POST['pg_txnid'];
    $mer_trxnid=$_POST['mer_txnid'];
    $card=$_POST['card_type'];
   
    echo $paystatus."</br>";
    echo $amount."</br>";
    echo $trxnid."</br>";
    echo $mer_trxnid."</br>";
    echo $card."</br>";
    
    // echo $paystatus;
    // echo $amount;
    //you can get all parameter from post request
    //print_r($_POST);
}
?>

<html><title>Successful Payment</title>
<head>

</head>

  <body>
             
                    <table border="0" cellpadding="4" cellspacing="2" align="center" style="border-collapse:collapse;">
                   
                    </td></tr>
                    <tr><td>Your Payment Was: </td><td><?php echo "$paystatus"; ?></td></tr>
                    <tr><td>Your Due Was: </td><td><?php echo $amount;?> Taka</td></tr>
                    <tr><td>Transaction ID: </td><td><?php echo $txn_id;?></td></tr>
                    <tr><td><input onClick="location.href='tenanthome.php'" type="submit" class='button' value="Go back to your user page" name="pay"><br/></td></tr></table></center>
                    
                   
                    
  
   
                    
                    </body></html>