<?php

require('fpdf\fpdf.php');

//test variables
// $name = "Shovon Khan";
// $amount = 50000;
// $paystatus = "Success";
// $txn_id = "asfaddgd";
// $time = "10-02-2022";
// $cust_email = "shovn@yahoo.com";
//


$name = $_COOKIE["name"];
$paystatus = $_COOKIE['paystatus'];
$cust_email = $_COOKIE['cust_email'];
$txn_id = $_COOKIE['txn_id'];
$amount = $_COOKIE['amount'];
$time = $_COOKIE['time'];


class PDF extends FPDF
{
// Page header
function Header()
{
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    //$this->Cell(50,10,'Abashon.com',1,0,'C');
    // Logo
    $this->Image('images\abashon.png',90,6,30);
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}



//
                   

//


// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',18);
// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Printing line number '.$i,0,1);
// Move to the right
$pdf->Cell(80);
$pdf->Cell(0,30,'Your Bill',0,1);
$pdf->Cell(30);
$pdf->SetFont('Times','',15);
$pdf->Cell(0,8,'Customer name:   '.$name,0,1);
$pdf->Cell(30);
$pdf->Cell(0,8,'Customer email:   '.$cust_email,0,1);
$pdf->Cell(30);
$pdf->Cell(0,8,'Payment status:    '.$paystatus,0,1);
$pdf->Cell(30);
$pdf->Cell(0,8,'Paid amount:        '.$amount.' Taka',0,1);
$pdf->Cell(30);
$pdf->Cell(0,8,'Transaction ID:    '.$txn_id,0,1);
$pdf->Cell(30);
$pdf->Cell(0,8,'Date:                    '.$time,0,1);

$pdf->Output();


?>