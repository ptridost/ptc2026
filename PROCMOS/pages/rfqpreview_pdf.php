<?php
require('../vendor/fpdf/fpdf.php');
require_once("../DatabaseConnection/databaseconnection.php");
// require_once("../operations/insertVisitors.php");

$pr_NoQ = $_GET['ID'];

// var_dump($nowDateOnlyISO);
// die();

// $acctID = '2';
// $purchaserequestID = '2';


if(!empty($pr_NoQ)){
    //proceed

    // $tour_RN = $_GET['trn'];
    // $tourName = $_GET['name'];
    // $tourSched = $_GET['due'];
    // $currentDate = date("F d, Y");

    // $spacePOS = strpos($tourSched," ");
    // $dateOnly = substr($tourSched,0,$spacePOS);
    // $timeOnly = substr($tourSched,$spacePOS);

    // $myDateTime = DateTime::createFromFormat('Y-m-d', $dateOnly);
    // $newDateString = $myDateTime->format('d F Y');

    // var_dump($timeOnly);
    // die();

    require_once("../operations/getpreview_RFQ.php");
    // require_once("../operations/generateCRN.php");
    

}
else{
    // echo '<script type="text/javascript">alert("NO URL ID!");</script>';
    // header("Location: index.php");
}

class PDF extends FPDF
  {
    // Page header
    function Header()
        {
            if ( $this->PageNo() == 1 ) {
                
                $this->Ln(0);
                $this->Cell(10);
                $this->SetFont('Arial','',8);
                $this->Cell(185,5,'FAD-PPGSS Form No. 18',0,0,"R");
                $this->Ln(5);
                $this->Cell(10);
                $this->SetFont('Arial','',8);
                $this->Cell(185,5,'Rev. 02/15-05-17',0,0,"R");
                // Line break
                $this->Ln(0);
            }
        }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        // $this->SetY(-15);
        // // Arial italic 8
        // $this->SetFont('Arial','I',8);
        // // Page number
        // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

   function SetDash($black=null, $white=null)
      {
          if($black!==null)
              $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
          else
              $s='[] 0 d';
          $this->_out($s);
      }
  }
  // Instanciation of inherited class
        $pdf = new PDF();
        $pdf->AddPage('P','A4',0); //page layout = portrait/landscape, 'paper size', 'not sure'
        $pdf->SetMargins(-5,1);
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial','IB',10);
        $pdf->Cell(175,10,'PHILIPPINE TEXTILE RESEARCH INSTITUTE',0,0,'C');
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(175,10,'General Santos Avenue, Bicutan, Taguig City',0,0,'C');
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(175,10,'Telephone No. 8372071 Local 2373 & 2366; Telefax No. 8370744',0,0,'C');
        $pdf->Ln(8);
        $pdf->Cell(20);
        $pdf->SetFont('Arial','IB',10);
        $pdf->Cell(175,10,'Request for Quotation',0,0,'C');

        $pdf->Ln(10);
        $pdf->Cell(140);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'Date:',0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30,5,$nowDateOnlyISO,'B',0,'L');
        
        $pdf->Ln(5);
        $pdf->Cell(140);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'Quotation No.',0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30,5,$Quotation_numcrn,'B',0,'L');

        $pdf->Ln(5);
        $pdf->Cell(15);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(28,5,'Company Name:',0,0,'L');
        $pdf->Cell(40,5,$requestName,'B',0);

        $pdf->Cell(57);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'PR No.',0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30,5,$pr_NoQ,'B',0,'L');

        $pdf->Ln(5);
        $pdf->Cell(15);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(15,5,'Address:',0,0,'L');
        $pdf->Cell(53,5,$requestName,'B',0);

        $pdf->Cell(57);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'End-user',0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(30,5,$ictclearance_requisitioner,'B',0,'L');

        $pdf->Ln(8);
        $pdf->Cell(25);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(17,5,'Please quote your lowest price on the item/s listed below, subject to the General Conditions on the last page,',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(22);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(17,5,'stating the shortest time of delivery and submit your quotation duly signed by your representative not later than',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(22);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,$requestName,'B',0,'L');
        $pdf->Cell(65,5,'in the return envelope attached herewith.',0,0);

        // var_dump($ppgss_esig);
        // die();

        if(!empty($ppgss_esig)){
            $pdf->Cell(10);
            $pdf->Cell(100,7,$pdf->Image('../photos/'.$ppgss_esig, $pdf->GetX() + 10, $pdf->GetY() + 2, 20),'',0,'C');
            // $pdf->Cell(50,7,'test','',0,'C');
        }
        // else{
        //     $pdf->Cell(10);
        //     $pdf->Cell(100,7,' ','LR',0,'L');
        //     // $pdf->Cell(63,7,' ','LR',0,'L');
        // }

        // $ppgss_esig

        $pdf->Ln(8);
        $pdf->Cell(140);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50,5,'MARILOU G. AGUSTIN','B',0,'C');

        $pdf->Ln(5);
        $pdf->Cell(140);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,'Administrative Officer V',0,0,'C');

        $pdf->Ln(6);
        $pdf->Cell(15);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(17,5,'NOTE:',0,0,'L');
        $pdf->Cell(5,5,'1.',0,0,'L');

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,5,'ALL ENTRIES MUST BE TYPEWRITTEN.',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(32);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(5,5,'2.',0,0,'L');

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(47,5,'DELIVERY PERIOD WITHIN',0,0,'L');
        $pdf->Cell(30,5,$requestName,'B',0,'L');
        $pdf->Cell(33,5,'CALENDAR DAYS.',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(32);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(5,5,'3.',0,0,'L');

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(10,5,'WARRANTY SHALL BE FOR A PERIOD OF, FROM THE ACCEPTANCE BY THE PTRI:',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(50);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,'a. ONE (1) YEAR FOR EQUIPMENT,',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(50);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,'b. SIX (6) MONTHS FOR SUPPLIES & MATERIALS',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(32);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(5,5,'4.',0,0,'L');

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(80,5,'PRICE VALIDITY SHALL BE FOR A PERIOD OF',0,0,'L');
        $pdf->Cell(23,5,$requestName,'B',0,'L');
        $pdf->Cell(32,5,'CALENDAR DAYS',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(32);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(5,5,'5.',0,0,'L');

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(10,5,'PhilGEPS REGISTRATION CERTIFICATE SHALL BE ATTACHED UPON SUBMISSION OF',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(37);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,'THE QUOTATION.',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(32);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(5,5,'6.',0,0,'L');

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(10,5,'BIDDERS SHALL SUBMIT ORIGINAL BROCHURES SHOWING CERTIFICATIONS OF',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(37);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(5,5,'THE PRODUCT BEING OFFERED.',0,0,'L');

        $pdf->Ln(8);
        $pdf->Cell(15);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(15,4,'ITEM','LRT',0,'C');
        $pdf->Cell(125,8,'ITEM AND DESCRIPTION',1,0,'C');
        $pdf->Cell(20,8,'QTY',1,0,'C');
        $pdf->Cell(30,8,'UNIT PRICE',1,0,'C');

        $pdf->Ln(4);
        $pdf->Cell(15);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(15,4,'NO.','LRB',0,'C');

      
        if(count($ictdetails_description) > 12){

            for ($i=0; $i < count($ictdetails_description); $i++) { 

                $pdf->Ln(4);
                $pdf->Cell(15);
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell(15,4,$i+1,1,0,'C');
                $pdf->Cell(125,4,$ictdetails_description[$i],1,0,'L');
                $pdf->Cell(20,4,$ictdetails_qty[$i],1,0,'C');
                $pdf->Cell(30,4,$requestName,1,0,'C');

               
            }
        }
        else{
            for ($i=0; $i < 12; $i++) {


                $pdf->Ln(4);
                $pdf->Cell(15);
                $pdf->SetFont('Arial','B',9);
                if($i < count($ictdetails_description)){
                    $pdf->Cell(15,4,$i+1,1,0,'C');
                }
                else{
                    $pdf->Cell(15,4,$requestName,1,0,'C');
                }
                $pdf->Cell(125,4,$ictdetails_description[$i],1,0,'L');
                $pdf->Cell(20,4,$ictdetails_qty[$i],1,0,'C');
                $pdf->Cell(30,4,$requestName,1,0,'C'); 

            }
        }

        

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        // $pdf->Ln(4);
        // $pdf->Cell(15);
        // $pdf->SetFont('Arial','B',9);
        // $pdf->Cell(15,4,$requestName,1,0,'C');
        // $pdf->Cell(125,4,$requestName,1,0,'C');
        // $pdf->Cell(20,4,$requestName,1,0,'C');
        // $pdf->Cell(30,4,$requestName,1,0,'C');

        $pdf->Ln(5);
        $pdf->Cell(100);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,5,'Brand and Model',0,0,'L');
        $pdf->Cell(39);
        $pdf->Cell(2,5,':',0,0);
        $pdf->Cell(49,5,$requestName,'B',0);


        $pdf->Ln(5);
        $pdf->Cell(100);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,5,'Delivery Period',0,0,'L');
        $pdf->Cell(39);
        $pdf->Cell(2,5,':',0,0);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(100);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,5,'Warranty',0,0,'L');
        $pdf->Cell(39);
        $pdf->Cell(2,5,':',0,0);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(100);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(15,5,'Price Validity',0,0,'L');
        $pdf->Cell(39);
        $pdf->Cell(2,5,':',0,0);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(25);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(165,5,'After having carefully read and accepted your General Conditions, I/We quote you on the item at prices noted above.',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,'Printed Name / Signature',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,'Tel. No. / Cellphone No.',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,'E-mail Address',0,0,'L');

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,$requestName,'B',0);

        $pdf->Ln(5);
        $pdf->Cell(156);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(49,5,'Date',0,0,'L');

































  

$pdf->Output();

?>