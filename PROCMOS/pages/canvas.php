<?php
require('../vendor/fpdf/fpdf.php');

class PDF extends FPDF
  {
    // Page header
    function Header()
        {
            if ( $this->PageNo() == 1 ) {
                
                $this->Ln(0);
                $this->Cell(5);
                $this->SetFont('Arial','',8);
                $this->Cell(325,5,'FAD-PPGSS Form No. 1b',0,0,'R');
                $this->Ln(5);
                $this->Cell(5);
                $this->SetFont('Arial','',8);
                $this->Cell(325,5,'Rev. 0/15-07-09',0,0,'R');
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
    $pdf->AddPage('L','Legal',0); //page layout = portrait/landscape, 'paper size', 'not sure'
    $pdf->SetMargins(-5,1);
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(325,10,'Department of Science and Technology',0,0,'C');
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(325,10,'PHILIPPINE TEXTILE RESEARCH INSTITUTE',0,0,'C');
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(325,10,'General Santos Avenue, Bicutan Taguig City',0,0,'C');
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(325,10,'ABSTRACT OF CANVAS',0,0,'C');
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(147,10,'No.',0,0,'R');
    $pdf->Cell(17,7,$numberName,'B',0);
    $pdf->Cell(7,10,'dtd.',0,0);
    $pdf->Cell(20,7,$numberName1,'B',0);
    
    $pdf->Ln(15);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(15,5,'PR No.',0,0,'L');
    $pdf->Cell(20,5,$prName,'B',0,);
    $pdf->Cell(8,5,'dtd.',0,0,'L');
    $pdf->Cell(20,5,$dtdName1,'B',0,);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(15,5,'RFQ No.',0,0,'L');
    $pdf->Cell(20,5,$rfqName,'B',0,);
     $pdf->Cell(8,5,'dtd.',0,0,'L');
    $pdf->Cell(20,5,$dtdName2,'B',0,);

    $pdf->Ln(6);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,10,'Item No.',1,0,'C');
    $pdf->Cell(15,10,'Qty.',1,0,'C');
    $pdf->Cell(15,10,'Unit',1,0,'C');
    $pdf->Cell(68,10,'Particulars',1,0,'C');
    $pdf->Cell(68,10,'Supplier1',1,0,'C');
    $pdf->Cell(68,10,'Supplier2',1,0,'C');
    $pdf->Cell(68,10,'Supplier3',1,0,'C');

    $pdf->Ln(10);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LRB',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LBR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LBR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);
    $pdf->Cell(68,5,'','R',0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(20,5,'','LBR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(15,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    $pdf->Cell(68,5,'','BR',0);
    
    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(186,5,'Based on the above mentioned astract of canvas, the bid is awarded to:',0,0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(68,5,'Bids evaluated and awarded:',0,0);
    $pdf->Cell(68,5,'Technical Working Group:',0,0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(55,5,$itemsName1,'B',0);
    $pdf->Cell(16,5,'Item/s No.',0,0);
    $pdf->Cell(20,5,$itemName1,'B',0);


    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(55,5,$itemsName2,'B',0);
    $pdf->Cell(16,5,'Item/s No.',0,0);
    $pdf->Cell(20,5,$itemName2,'B',0);
    $pdf->Cell(95);
    $pdf->Cell(68,5,$chairpersonName,'B',0);
    $pdf->Cell(5);
    $pdf->Cell(63,5,$chairpersonName,'B',0);


    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(55,5,$itemsName3,'B',0);
    $pdf->Cell(16,5,'Item/s No.',0,0);
    $pdf->Cell(20,5,$itemName3,'B',0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(95);
    $pdf->Cell(68,5,'Chairperson',0,0,'L');
    $pdf->Cell(5);
    $pdf->Cell(63,5,'Head, TWG',0,0);

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(55,5,$itemsName4,'B',0);
    $pdf->Cell(16,5,'Item/s No.',0,0);
    $pdf->Cell(20,5,$itemName4,'B',0);
    $pdf->Cell(95);
    $pdf->Cell(68,5,$witnessName,'B',0,'R');

    $pdf->Ln(5);
    $pdf->Cell(20);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(55,5,$itemsName5,'B',0);
    $pdf->Cell(16,5,'Item/s No.',0,0);
    $pdf->Cell(20,5,$itemName2,'B',0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(95);
    $pdf->Cell(68,5,'Vice Chairperson',0,0,'L');
    $pdf->Cell(5);
    $pdf->Cell(68,5,'Member',0,0);

    $pdf->Ln(5);
    $pdf->Cell(206);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(17,5,'Members:',0,0);

    $pdf->Cell(57);
    $pdf->Cell(63,5,$membersName,'B',0);

    $pdf->Ln(5);
    $pdf->Cell(207);
    $pdf->Cell(68,5,$memberName1,'B',0);
    $pdf->Cell(5);
    $pdf->Cell(63,5,$memberName2,'B',0);

    $pdf->Ln(5);
    $pdf->Cell(207);
    $pdf->Cell(68,5,$memberName1,'B',0);
    $pdf->Cell(5);
    $pdf->Cell(63,5,$memberName2,'B',0);

    $pdf->Ln(5);
    $pdf->Cell(207);
    $pdf->Cell(15,5,'Enduser:',0,0);
    $pdf->Cell(53,5,$enduserName,'B',0);
    $pdf->Cell(5);
    $pdf->Cell(15,5,'Witness:',0,0);

    $pdf->Ln(5);
    $pdf->Cell(280);
    $pdf->Cell(63,5,$witnessName2,'B',0);

    
    

    































$pdf->Output();

?>