<?php
require('../vendor/fpdf/fpdf.php');
require_once("../DatabaseConnection/databaseconnection.php");
// require_once("../operations/insertVisitors.php");

$ictclearance_ID = $_GET['ID'];

// $acctID = '2';
// $purchaserequestID = '2';

if(!empty($ictclearance_ID)){
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

    require_once("../operations/get_ICTClearance.php");


}
else{
    echo '<script type="text/javascript">alert("NO URL ID!");</script>';
    header("Location: index.php");
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
                $this->Cell(185,5,'PICTS Form No. 002',0,0,"R");
                $this->Ln(5);
                $this->Cell(10);
                $this->SetFont('Arial','',8);
                $this->Cell(185,5,'Rev. 1/ 12-05-17',0,0,"R");
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
    $pdf->Cell(10);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(205,10,'PHILIPPINE TEXTILE RESEARCH INSTITUTE',0,0,"C");
    $pdf->Ln(5);
    $pdf->Cell(10);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(205,10,'Planning and ICT Staff',0,0,"C");
    $pdf->Ln(10);
    $pdf->Cell(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(205,10,'ICT PURCHASE CLEARANCE SLIP',0,0,"C");

    $pdf->Ln(15);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'Requisitioner:','TL',0);
        $pdf->Cell(75,10,$ictclearance_requisitioner,1,0,'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,'Date:','TR',0);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(50,5,'ICT Clearance No.:','TR',0);

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->Cell(100,5,'','L',0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,$ictclearance_dateNew,'R',0, 'C');
        $pdf->Cell(50,5,$ictclearance_No,'R',0, 'C');

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(25,5,'Quantity','LTB',0,"C");
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(75,5,'Particular/Description','LRB',0,"C");
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,5,'Remarks:','TR',0);
       
        for ($i=0; $i < count($ictdetails_qty); $i++) { 

            if($i == 0){
                $pdf->Ln(5);
                $pdf->Cell(10);
                $pdf->Cell(25,10,$ictdetails_qty[$i],'LR',0,'C');
                $pdf->Cell(75,10,$ictdetails_description[$i],'LR',0,);
                $pdf->Cell(100,10,$ictclearance_remarks,'LR',0);
            }
            else{
                $pdf->Ln(5);
                $pdf->Cell(10);
                $pdf->Cell(25,10,$ictdetails_qty[$i],'LR',0,'C');
                $pdf->Cell(75,10,$ictdetails_description[$i],'LR',0,);
                $pdf->Cell(100,10,'','LR',0);
            }
        }

        if(count($ictdetails_qty) > 3){
            //add two rows
            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);
        }
        else{
            //add many rows
            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);
        }

        

        

        $pdf->Ln(10);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,10,'Reviewed by:',"LRT",0,"C");
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,10,'Approved by:','LRT',0);
        $pdf->Ln(10);
        if(!empty($ictclearance_reviewedesig)){
            $pdf->Cell(10);
            $pdf->Cell(100,7,$pdf->Image('../photos/'.$ictclearance_reviewedesig, $pdf->GetX() + 40, $pdf->GetY(), 20),'LR',0,'C');
            // $pdf->Cell(50,7,'test','',0,'C');
        }
        else{
            $pdf->Cell(10);
            $pdf->Cell(100,7,' ','LR',0,'L');
            // $pdf->Cell(63,7,' ','LR',0,'L');
        }
        if(!empty($ictclearance_approvedesig)){
            if(!empty($ictclearance_reviewedesig)){
                //ICT CLEARANCE APPROVED ESIG CANNOT BE POSSIBLE IF THERE IS NO ICT CLEARANCE REVIEWED ESIG
                $pdf->Cell(100,7,$pdf->Image('../photos/'.$ictclearance_approvedesig, $pdf->GetX() + 40, $pdf->GetY(), 20),'LR',0,'C');
                // $pdf->Cell(50,7,'test','',0,'C');
            }
            else{
                $pdf->Cell(100,7,' ','LR',0,'L');
            }
        }
        else{
            $pdf->Cell(100,7,' ','LR',0,'L');
            // $pdf->Cell(63,7,' ','LR',0,'L');
        }
        $pdf->Ln(2);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',9);
        // if(!empty($ictclearance_reviewedesig)){
        //     $pdf->Cell(88,7,$pdf->Image('../photos/'.$ictclearance_reviewedesig, $pdf->GetX() + 35, $pdf->GetY() - 1, 20),'',0,'C');
        // }
        // else{
        //     // $pdf->Cell(88,7,' ','LR',0,'L');
        //     // $pdf->Cell(63,7,' ','LR',0,'L');
        // }

        $pdf->Cell(100,10,'______________________________','LR',0,"C");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'______________________________','LR',0,"C");
        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'PICTS Staff','LRB',0,"C");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'Head PICTS','LRB',0,"C");

        $pdf->Ln(20);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(200,10,'PICTS Form No. 002',0,0,"R");
        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(200,10,'Rev. 1/ 12-05-17',0,0,"R");
        
        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(205,10,'PHILIPPINE TEXTILE RESEARCH INSTITUTE',0,0,"C");
        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(205,10,'Planning and ICT Staff',0,0,"C");
        $pdf->Ln(10);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(205,10,'ICT PURCHASE CLEARANCE SLIP',0,0,"C");

        $pdf->Ln(15);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(25,5,'Requisitioner:','TL',0);
        $pdf->Cell(75,10,$ictclearance_requisitioner,1,0,'C');
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,'Date:','TR',0);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(50,5,'ICT Clearance No.:','TR',0);

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->Cell(100,5,'','L',0);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(50,5,$ictclearance_dateNew,'R',0, 'C');
        $pdf->Cell(50,5,$ictclearance_No,'R',0, 'C');

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(25,5,'Quantity','LTB',0,"C");
        $pdf->SetFont('Arial','I',9);
        $pdf->Cell(75,5,'Particular/Description','LRB',0,"C");
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,5,'Remarks:','TR',0);


        

        for ($i=0; $i < count($ictdetails_qty); $i++) { 

            if($i == 0){
                $pdf->Ln(5);
                $pdf->Cell(10);
                $pdf->Cell(25,10,$ictdetails_qty[$i],'LR',0,'C');
                $pdf->Cell(75,10,$ictdetails_description[$i],'LR',0,);
                $pdf->Cell(100,10,$ictclearance_remarks,'LR',0);
            }
            else{
                $pdf->Ln(5);
                $pdf->Cell(10);
                $pdf->Cell(25,10,$ictdetails_qty[$i],'LR',0,'C');
                $pdf->Cell(75,10,$ictdetails_description[$i],'LR',0,);
                $pdf->Cell(100,10,'','LR',0);
            }
        }
        // $pdf->Ln(5);
        // $pdf->Cell(10);
        // $pdf->Cell(25,10,'test','LR',0,'C');
        // $pdf->Cell(75,10,'test','LR',0,);
        // // $pdf->MultiCell(100,5,'The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog. ',R,'J');
        // // // $pdf->MultiCell(160,5,$ictclearance_remarks,'','L');
        // $pdf->Cell(100,10,$ictclearance_remarks,'LR',0);
        if(count($ictdetails_qty) > 5){
            //add two rows
            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);
        }
        else{
            //add many rows
            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);

            $pdf->Ln(5);
            $pdf->Cell(10);
            $pdf->Cell(25,10,'','LR',0,'C');
            $pdf->Cell(75,10,'','LR',0);
            $pdf->Cell(100,10,'','LR',0);
        }
        

        

        // $pdf->Ln(5);
        // $pdf->Cell(10);
        // $pdf->Cell(25,10,'','LR',0,'C');
        // $pdf->Cell(75,10,'','LR',0);
        // $pdf->Cell(100,10,'','LR',0);

        // $pdf->Ln(5);
        // $pdf->Cell(10);
        // $pdf->Cell(25,10,'','LR',0,'C');
        // $pdf->Cell(75,10,'','LR',0);
        // $pdf->Cell(100,10,'','LR',0);

        $pdf->Ln(10);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,10,'Reviewed by:',"LRT",0,"C");
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(100,10,'Approved by:','LRT',0);
        $pdf->Ln(10);


        
        if(!empty($ictclearance_reviewedesig)){
            $pdf->Cell(10);
            $pdf->Cell(100,7,$pdf->Image('../photos/'.$ictclearance_reviewedesig, $pdf->GetX() + 40, $pdf->GetY(), 20),'LR',0,'C');
            // $pdf->Cell(50,7,'test','',0,'C');
        }
        else{
            $pdf->Cell(10);
            $pdf->Cell(100,7,' ','LR',0,'L');
            // $pdf->Cell(63,7,' ','LR',0,'L');
        }
        if(!empty($ictclearance_approvedesig)){
            if(!empty($ictclearance_reviewedesig)){
                //ICT CLEARANCE APPROVED ESIG CANNOT BE POSSIBLE IF THERE IS NO ICT CLEARANCE REVIEWED ESIG
                $pdf->Cell(100,7,$pdf->Image('../photos/'.$ictclearance_approvedesig, $pdf->GetX() + 40, $pdf->GetY(), 20),'LR',0,'C');
                // $pdf->Cell(50,7,'test','',0,'C');
            }
            else{
                $pdf->Cell(100,7,' ','LR',0,'L');
            }
        }
        else{
            $pdf->Cell(100,7,' ','LR',0,'L');
            // $pdf->Cell(63,7,' ','LR',0,'L');
        }

        $pdf->Ln(2);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'______________________________','LR',0,"C");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'______________________________','LR',0,"C");
        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'PICTS Staff','LRB',0,"C");
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(100,10,'Head PICTS','LRB',0,"C");
    

$pdf->Output();

?>