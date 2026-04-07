<?php
require_once('../vendor/fpdi/src/autoload.php');
use setasign\Fpdi\Fpdi;
// or for usage with TCPDF:
// use setasign\Fpdi\Tcpdf\Fpdi;

// or for usage with tFPDF:
// use setasign\Fpdi\Tfpdf\Fpdi;

// setup the autoload function

// initiate FPDI
$pdf = new Fpdi();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("../RFQs/papernix-rfq.pdf");
// import page 1
$tplId = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplId, 10, 10, 100);

$pdf->Output();  

?>