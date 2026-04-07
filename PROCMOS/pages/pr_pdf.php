<?php
require('../vendor/fpdf/fpdf.php');
// require_once('../vendor/fpdi/src/autoload.php');
require_once("../DatabaseConnection/databaseconnection.php");
// require_once("../operations/insertVisitors.php");

$purchaserequestID = $_GET['ID'];
$acctID = $_GET['acctID'];

// $acctID = '2';
// $purchaserequestID = '2';

if(!empty($acctID)){
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
    
    require_once("../operations/getPR.php");


}
else{
    echo '<script type="text/javascript">alert("NO URL ID!");</script>';
    header("Location: index.php");
}

	class PDF extends FPDF
	{


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

        // ICT Page header
        function Header()
        {
            if ( $this->PageNo() == 2 ) {
                
                $this->Ln(10);
                $this->Cell(25);
                $this->SetFont('Arial','',8);
                $this->Cell(185,5,'PICTS Form No. 002',0,0,"R");
                $this->Ln(5);
                $this->Cell(20);
                $this->SetFont('Arial','',8);
                $this->Cell(185,5,'Rev. 1/ 12-05-17',0,0,"R");
                // Line break
                $this->Ln(0);
            }
        }

		function HeaderTable()
		{
		    $this->SetFont('Arial', 'B', 9);
		    $this->Cell(20,7,'Stock No.',1,0,'C');
		    $this->Cell(13,7,'Unit',1,0,'C');
		    $this->Cell(88,7,'Item Description',1,0,'C');
            $this->Cell(13,7,'Qty',1,0,'C');
		    $this->Cell(20,7,'Unit Cost',1,0,'C');
		    $this->Cell(30,7,'Total Cost',1,0,'C');
		    // $this->Ln(10);
    		// $this->Cell(20);
		    // $this->Cell(40,10,'a request','LBR',0,'C');
		    // $this->Cell(40,10,'10 ml crude extract','LBR',0,'C');
		    // $this->Cell(13,10,'2','LBR',0,'C');
		    // $this->Cell(30,10,'PHP 250.00','LBR',0,'C');
		    // $this->Cell(40,10,'PHP 500.00','LBR',0,'C');
		    // $this->Ln(10);
    		// $this->Cell(20);
		    // $this->Cell(40,10,'b request','LBR',0,'C');
		    // $this->Cell(40,10,'5 ml crude extract','LBR',0,'C');
		    // $this->Cell(13,10,'5','LBR',0,'C');
		    // $this->Cell(30,10,'PHP 150.00','LBR',0,'C');
		    // $this->Cell(40,10,'PHP 750.00','LBR',0,'C');
		    // $this->Ln(10);
    		// $this->Cell(20);
		    // $this->Cell(40,10,'c request','LBR',0,'C');
		    // $this->Cell(40,10,'3 ml crude extract','LBR',0,'C');
		    // $this->Cell(13,10,'3','LBR',0,'C');
		    // $this->Cell(30,10,'PHP 85.00','LBR',0,'C');
		    // $this->Cell(40,10,'PHP 255.00','LBR',0,'C');
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
    // $pdf->SetFont('Arial','B',11);
    // $pdf->Ln(12);
    // $pdf->Cell(20);
    // $pdf->Cell(180,10,'TOUR CONFIRMATION',0,0,'C');
    // Page header
        
    // Logo
    $pdf->Image('../photos/ptrilogo.jpg',75,$pdf->GetY() + 13,10);
    // $this->Image('../photos/TUV-Rheinland.jpg',170,20,22);
    // Arial bold 11
    $pdf->SetFont('Arial','',8);
    // Move to the right
    if(!empty($pr_ppgssesig)){
        $pdf->Cell(155);       
        $pdf->Cell(12.5,0,'FAD-PPGSS Form No. 15');
        $pdf->Ln(4);
        // $pdf->Cell(167);

        $pdf->Cell(20);  
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50,0,'BY: _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ ');

        $pdf->Cell(112.5);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,'Rev. 1/15-05-17');
        //PPGSS SIGN
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_ppgssesig, $pdf->GetX() - 190, $pdf->GetY() - 3, 10),'',0,'C');
        //CHIEF, FAD SIGN
        if(!empty($pr_chieffadesig)){
            $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_chieffadesig, $pdf->GetX() - 260, $pdf->GetY() - 3, 10),'',0,'C');
        }
        
        $pdf->Ln(7);
    }
    else{
        $pdf->Cell(155);       
        $pdf->Cell(12.5,0,'FAD-PPGSS Form No. 15');
        $pdf->Ln(4);
        $pdf->Cell(182.5);
        $pdf->Cell(0,0,'Rev. 1/15-05-17');
        $pdf->Ln(7);
    }   
  
    

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20);
    $pdf->Cell(0,10,'PURCHASE REQUEST','',0,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(0,10,'Philippine Textile Research Institute','',0,'C');
         
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(10);
    $pdf->Cell(20);
    $pdf->Cell(15,10,'Division:','LT',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(100,10,$acct_DivisionEnd,'RT',0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Cell(45,10,'P.R. No.','TR',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(14,10,'P.R. No.','T',0,'L');
    $pdf->SetFont('Arial','',16);
    $pdf->SetTextColor(124 , 62, 179);
    $pdf->Cell(27,10,$purchrequest_No,'T',0,'C');
    $pdf->SetTextColor(0 , 0, 0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(5,10,'dtd.','T',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetTextColor(158 , 94, 215);
    $pdf->Cell(23,10,strtoupper($prNo_dtdNew),'TR',0,'C');
    $pdf->SetTextColor(0 , 0, 0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(10);
    $pdf->Cell(20);
    $pdf->Cell(14,10,'Section:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(101,10,$acct_SectionEnd,'BR',0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Cell(45,10,'SAI No.','B',0,'L');
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(24,10,'dtd.','BR',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(41,10,'SAI No.','B',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(28,10,'dtd.','BR',0,'L');
    $pdf->Ln(10);
    $pdf->Cell(20);









    $pdf->HeaderTable();
    $pdf->SetFont('Arial', '', 9);
    for ($i=0; $i < count($prd_Stock); $i++) { 
        $pdf->Ln(7);
        $pdf->Cell(20);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,7,$prd_Stock[$i],'LBR',0,'C');
        $pdf->Cell(13,7,$prd_Unit[$i],'LBR',0,'C');
        $pdf->Cell(88,7,$prd_Description[$i],'LBR',0,'L');
        
        // $pdf->MultiCell(87,5,$prd_Description[$i]);
        // $pdf->Cell(50);
        // $pdf->Cell(141,'','','LBR',0,'C');
        $pdf->Cell(13,7,$prd_Qty[$i],'LBR',0,'C');
        $pdf->Cell(20,7,$prd_UnitCost[$i],'LBR',0,'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30,7,$prd_TotalCost[$i],'LBR',0,'C');
    }



    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Charging:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(121,7,$purchrequest_charging,'BR',0,'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30,7,'PHP '.$purchrequest_totalfee,'LBR',0,'C');


    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Purpose:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(151,7,$purchrequest_purpose,'BR',0,'L');



    $pdf->SetFont('Arial','',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,' ','LR',0,'L');
    // $pdf->SetFont('Arial','',8);
    $pdf->Cell(88,7,'Requested by:','R',0,'L');
    $pdf->Cell(63,7,'Approved by:','R',0,'L');
    // $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Signature','LR',0,'L');
    // $pdf->Image('../photos/procurement.jpg',88,75,10);
    // $pdf->Image('../photos/test-esig.jpg',83,92,20);
    // $pdf->Image('http://chart.googleapis.com/chart?cht=p3&chd=t:60,40&chs=250x100&chl=Hello|World', 60, 30, 90, 0, 'PNG');
    // $pdf->Image('https://services.dost-ptri.com/photos/service.png', 60, 30, 90, 0, 'PNG');
    // $pdf->Image('logo.png', 10, 10, -300);
    // $pdf->Cell(88,7,'','',0,'C');
    // $pdf->Cell(88,7,$pdf->Image('../photos/test-esig.jpg', $pdf->GetX() + 35, $pdf->GetY() - 1, 20),'',0,'C');
    if(!empty($pr_requestoresig)){
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_requestoresig, $pdf->GetX() + 35, $pdf->GetY() - 1, 20),'',0,'C');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }
    else{
        $pdf->Cell(88,7,' ','LR',0,'L');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }  

    if(!empty($pr_approveesig)){
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_approveesig, $pdf->GetX() - 40, $pdf->GetY() - 1, 20),'',0,'C');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }
    else{
        $pdf->Cell(88,7,' ','LR',0,'L');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }


    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Printed Name','LR',0,'L');
    $pdf->SetFont('Arial','BU',9);
    $pdf->Cell(88,7,strtoupper($req_Name),'R',0,'C');
    $pdf->Cell(63,7,strtoupper($appr_Name),'R',0,'C');
    $pdf->SetFont('Arial','',9);
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Designation','LR',0,'L');
    $pdf->Cell(88,7,$req_Designation,'R',0,'C');
    $pdf->Cell(63,7,$appr_Designation,'R',0,'C');
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Date','LBR',0,'L');


    if(!empty($pr_requestoresig)){
        $pdf->Cell(88,7,$date_prrequestoresigNew,'B',0,'C');
        $pdf->Cell(63,7,' ','LRB',0,'L');
    }
    else{
        $pdf->Cell(88,7,' ','LRB',0,'L');
        $pdf->Cell(63,7,' ','LRB',0,'L');
    }


    // $pdf->Cell(88,7,' ','BR',0,'C');
    // $pdf->Cell(63,7,' ','BR',0,'C');

























    if(count($prd_Unit) > 3){
        $pdf->Ln(20);
    }
    else{
        $pdf->Ln(40);
    }

    $pdf->Image('../photos/ptrilogo.jpg',75,$pdf->GetY()+13,10);
    // $pdf->Image('../photos/TUV-Rheinland.jpg',170,20,22);
    // Arial bold 11


      


    $pdf->SetFont('Arial','',8);

    if(!empty($pr_ppgssesig)){
        $pdf->Cell(170);       
        $pdf->Cell(12.5,0,'FAD-PPGSS Form No. 15');
        $pdf->Ln(4);
        // $pdf->Cell(167);

        $pdf->Cell(20);  
        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(50,0,'BY: _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ ');

        $pdf->Cell(112.5);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0,0,'Rev. 1/15-05-17');
        //PPGSS SIGN
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_ppgssesig, $pdf->GetX() - 190, $pdf->GetY() - 3, 10),'',0,'C');
        //CHIEF, FAD SIGN
        if(!empty($pr_chieffadesig)){
            $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_chieffadesig, $pdf->GetX() - 260, $pdf->GetY() - 3, 10),'',0,'C');
        }

        $pdf->Ln(7);
    }
    else{
        $pdf->Cell(170);       
        $pdf->Cell(12.5,0,'FAD-PPGSS Form No. 15');
        $pdf->Ln(4);
        $pdf->Cell(182.5);
        $pdf->Cell(0,0,'Rev. 1/15-05-17');
        $pdf->Ln(7);
    }

    
    // Move to the right
    // $pdf->Cell(167);       
    // $pdf->Cell(12.5,0,'FAD-PPGSS Form No. 15');
    // $pdf->Cell(0,8,'Rev. 1/15-05-17');
    // $pdf->Ln(7);
    





    
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20);
    $pdf->Cell(0,10,'PURCHASE REQUEST','',0,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(0,10,'Philippine Textile Research Institute','',0,'C');




    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(10);

    $pdf->Cell(20);
    $pdf->Cell(15,10,'Division:','LT',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(100,10,$acct_DivisionEnd,'RT',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(14,10,'P.R. No.','T',0,'L');



    $pdf->SetFont('Arial','',16);
    $pdf->SetTextColor(124 , 62, 179);
    $pdf->Cell(27,10,$purchrequest_No,'T',0,'C');
    $pdf->SetTextColor(0 , 0, 0);
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(5,10,'dtd.','T',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetTextColor(158 , 94, 215);
    $pdf->Cell(23,10,strtoupper($prNo_dtdNew),'TR',0,'C');
    $pdf->SetTextColor(0 , 0, 0);
    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(10);
    $pdf->Cell(20);
    $pdf->Cell(14,10,'Section:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(101,10,$acct_SectionEnd,'BR',0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Cell(45,10,'SAI No.','B',0,'L');
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(24,10,'dtd.','BR',0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->Cell(41,10,'SAI No.','B',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(28,10,'dtd.','BR',0,'L');








    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(24,10,'dtd.','TR',0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(10);
    // $pdf->Cell(20);
    // $pdf->Cell(14,10,'Section:','LB',0,'L');
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(101,10,$acct_SectionEnd,'BR',0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Cell(45,10,'SAI No.','B',0,'L');
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(24,10,'dtd.','BR',0,'L');
    $pdf->Ln(10);
    $pdf->Cell(20);









    $pdf->HeaderTable();
    $pdf->SetFont('Arial', '', 9);
    for ($i=0; $i < count($prd_Stock); $i++) { 
        $pdf->Ln(7);
        $pdf->Cell(20);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(20,7,$prd_Stock[$i],'LBR',0,'C');
        $pdf->Cell(13,7,$prd_Unit[$i],'LBR',0,'C');
        $pdf->Cell(88,7,$prd_Description[$i],'LBR',0,'L');
        $pdf->Cell(13,7,$prd_Qty[$i],'LBR',0,'C');
        $pdf->Cell(20,7,$prd_UnitCost[$i],'LBR',0,'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30,7,$prd_TotalCost[$i],'LBR',0,'C');
    }





    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Charging:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(121,7,$purchrequest_charging,'BR',0,'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30,7,'PHP '.$purchrequest_totalfee,'LBR',0,'C');


    $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Purpose:','LB',0,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->Cell(151,7,$purchrequest_purpose,'BR',0,'L');



    $pdf->SetFont('Arial','',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,' ','LR',0,'L');
    // $pdf->SetFont('Arial','',8);
    $pdf->Cell(88,7,'Requested by:','R',0,'L');
    $pdf->Cell(63,7,'Approved by:','R',0,'L');
    // $pdf->SetFont('Arial','B',9);
    $pdf->Ln(7);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Signature','LR',0,'L');
    // $pdf->Cell(88,7,'','',0,'C');
    
    // $pdf->Cell(88,7,$pdf->Image('../photos/test-esig.jpg', $pdf->GetX() + 35, $pdf->GetY() - 1, 20),'',0,'C');
    // $pdf->Image('../photos/test-esig.jpg',83,226.5,20);
    // $pdf->Cell(88,7,' ','R',0,'L');
     if(!empty($pr_requestoresig)){
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_requestoresig, $pdf->GetX() + 35, $pdf->GetY() - 1, 20),'',0,'C');
        // $pdf->Cell(88,7,$pdf->Image('../photos/test-esig.jpg', $pdf->GetX() - 30, $pdf->GetY() + 10, 10),'',0,'C');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }
    else{
        
        $pdf->Cell(88,7,' ','LR',0,'L');
        $pdf->Cell(63,7,' ','LR',0,'L');
    } 


    if(!empty($pr_approveesig)){
        $pdf->Cell(88,7,$pdf->Image('../photos/'.$pr_approveesig, $pdf->GetX() - 40, $pdf->GetY() - 1, 20),'',0,'C');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }
    else{
        $pdf->Cell(88,7,' ','LR',0,'L');
        $pdf->Cell(63,7,' ','LR',0,'L');
    }


    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Printed Name','LR',0,'L');
    $pdf->SetFont('Arial','BU',9);
    $pdf->Cell(88,7,strtoupper($req_Name),'R',0,'C');
    $pdf->Cell(63,7,strtoupper($appr_Name),'R',0,'C');

    // $pdf->Cell(63,7,'CELIA B. ELUMBA','R',0,'C');
    $pdf->SetFont('Arial','',9);
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Designation','LR',0,'L');
    $pdf->Cell(88,7,$req_Designation,'R',0,'C');
    $pdf->Cell(63,7,$appr_Designation,'R',0,'C');
    // $pdf->Cell(63,7,'Director IV','R',0,'C');
    $pdf->Ln(4);
    $pdf->Cell(20);
    $pdf->Cell(33,7,'Date','LBR',0,'L');
    
    if(!empty($pr_requestoresig)){
        $pdf->Cell(88,7,$date_prrequestoresigNew,'B',0,'C');
        $pdf->Cell(63,7,' ','LRB',0,'L');
    }
    else{
        $pdf->Cell(88,7,' ','LRB',0,'L');
        $pdf->Cell(63,7,' ','LRB',0,'L');
    }
    // $pdf->Cell(88,7,' ','BR',0,'C');
    // $pdf->Cell(63,7,' ','BR',0,'C');
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(151,7,$tour_RN,'BR',0,'L');

    // $pdf->Cell(53.5,10,$tour_RN,'T',0,'L');
    // $pdf->Cell(9,10,'Date: ',0,0,'L');
    // $pdf->Cell(37,10,$currentDate,0,0,'L');
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(10);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'RECIPIENT:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(50,10,$tourName,0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(5);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'REQUESTING PARTY:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(50,10,$tourName,0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(5);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'DATE & TIME:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(50,10,$newDateString . " " . $timeOnly,0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(5);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'FACILITIES TO BE VISITED:',0,0);
    // $pdf->SetFont('Arial','',9);

    // $validateLabFaci = 0;
    // $validatePilotFaci = 0;

    // for ($i=0; $i < count($tourLabFaci); $i++) { 
    //     if(!empty($tourLabFaci[$i])){
    //         //do nothing yet
    //     }
    //     else{
    //         $validateLabFaci += 1;
    //     }
    // }

    // for ($i=0; $i < count($tourPilotFaci); $i++) { 
    //     if(!empty($tourPilotFaci[$i])){
    //         //do nothing yet
    //     }
    //     else{
    //         $validatePilotFaci += 1;
    //     }
    // }

    // $validateOthersFaci = 0;
    // for ($i=0; $i < count($tourOthersFaci); $i++) { 
    //     if(!empty($tourOthersFaci[$i])){
    //         //do nothing yet
    //     }
    //     else{
    //         $validateOthersFaci += 1;
    //     }
    // }

    // // var_dump($validateLabFaci . "\n " . $validatePilotFaci . "\n " . count($tourLabFaci) . "\n " . count($tourPilotFaci));
    // // die();

    // if($validateLabFaci == count($tourLabFaci)){
    //     //walang laman
    //     if($validatePilotFaci == count($tourPilotFaci)){
    //         //walang laman
    //         if($validateOthersFaci == count($tourOthersFaci)){
    //         //walang laman lahat
    //         }
    //         else{
    //         //other lang may laman
    //             for ($i=0; $i < count($tourOthersFaci); $i++) { 
    //                 if(!empty($tourOthersFaci[$i])){
    //                     $pdf->Cell(72,10,$tourOthersFaci[$i] . ", ",0,0); 
    //                 }
    //                 else{
    //                     //do nothing
    //                 }
    //             }
    //         }            
    //     }
    //     else{
    //         //Pilot may laman
    //         // for ($i=0; $i < count($tourPilotFaci); $i++) { 
    //         //     if(!empty($tourPilotFaci[$i])){
                    
    //         //     }
    //         //     else{
    //         //         //do nothing
    //         //     }
    //         // }
    //         $pdf->Cell(50,10,"TSD Pilot Plant",0,0); 
    //     }
    // }
    // else{
    //     if($validatePilotFaci == count($tourPilotFaci)){
    //         if($validateOthersFaci == count($tourOthersFaci)){
    //         //walang laman pilot at others pero meron lab
    //             $pdf->Cell(50,10,"Testing Laboratories",0,0); 
    //         }
    //         else{
    //         //meron laman lab at others
    //             // for ($i=0; $i < count($tourOthersFaci); $i++) { 
    //             //     if(!empty($tourOthersFaci[$i])){
    //             //         $pdf->Cell(72,10,$tourOthersFaci[$i] . ", ",0,0); 
    //             //     }
    //             //     else{
    //             //         //do nothing
    //             //     }
    //             // }
    //         }            
    //     }
    //     else{
    //         if($validateOthersFaci == count($tourOthersFaci)){
    //         //walang laman others, pero pilot at lab meron
    //             $pdf->Cell(50,10,"TSD Pilot Plant, ",0,0); 
    //             $pdf->Cell(50,10,"Testing Laboratories",0,0); 
    //         }
    //         else{
    //         //lahat meron laman
    //             // for ($i=0; $i < count($tourOthersFaci); $i++) { 
    //             //     if(!empty($tourOthersFaci[$i])){
    //             //         $pdf->Cell(72,10,$tourOthersFaci[$i] . ", ",0,0); 
    //             //     }
    //             //     else{
    //             //         //do nothing
    //             //     }
    //             // }
    //         }
    //     }
    // }
    

    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(5);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'PURPOSE OF VISIT:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(50,10,$tourObjective,0,0);







    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(15);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'Below are our few important reminders for the tour:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Ln(5);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'1. Kindly advise the participants to wear comfortable clothes as it will be hot in some parts of the pilot plant and in open areas;',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'2. Observe minimum health standards and protocols by bringing and wearing face mask at all times. Fill-out and submit the',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'Health Declaration Form before entry to the PTRI building using this link:',0,0);
    // $pdf->SetFont('Arial','IU',9);
    // $pdf->Cell(30,10,'https://tinyurl.com/PTRIHealthDeclaration.',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'Show the security guard the tour confirmation form to enter PTRI. Please note that for health protocol and validation',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'purposes, everyone will still have to undergo temperature and vaccination card checking before entry to the PTRI',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'premises and upon arrival in the lobby even if the Health Declaration Form has been accomplished before your visit.',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'3. Visitors are required to change to clean shoes before entering the TELA Gallery, Textile Design and Innovation Hub. They have',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'the option to use available laboratory shoes at the Gallery. Changing shoes is required before entry to each facility.',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'4. Participants may bring their own refreshments for consumption before or after the tour in designated areas;',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'5. You may bring extra face masks for emergency purposes. Please assess and inform participants (especially those with any',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'serious respiratory issues and allergies) that the plant tour exposes participants to different textile processing machines ',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'and fiber particles around. The institute shall not be liable for any injury that the participants may sustain or for losses',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'whether personal or pecuniary in the whole duration of the tour;',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'6. Onsite demonstration will not be part of the open facility tour as it is part of the training course we offer entitled',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'"Basic Textile Technology Course", which requires participants to enroll in their perferred course. Please see the',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'description of the said courses in this link if you wish to avail the training courses: bit.ly/PTRITrainingRegistration',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'7. In case you have processed and intended to take photos or videos for documentation purposes, please do not forget to bring',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'the four (4) originial copies of the signed Agreement on Provision of Technical Assistance on the day of the tour.',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(30);
    // $pdf->Cell(103,10,'Failure to bring the said documents will not be permitted to perform such activities.',0,0);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(45,10,'8. Bringing personal pens are highly encouraged.',0,0);




    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(15);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'PTRI-DOST',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'Sharmainne Rhey B. Caoili',0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'Coordinator:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'SRS II, OD - TIPS',0,0);


    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(12);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'Noted by:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'Evangeline Flor P. Manalang',0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'Head, OD - TIPS',0,0);




    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(12);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'Approved by:',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'Celia B. Elumba',0,0);
    // $pdf->SetFont('Arial','B',9);
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(19,10,'',0,0);
    // $pdf->SetFont('Arial','',9);
    // $pdf->Cell(15);
    // $pdf->Cell(40,10,'Director IV, Office of the Director',0,0);






    // $pdf->Ln(15);
    // $pdf->Cell(20);
    // $pdf->Cell(190,10,'DOST-PTRI Building, DOST South Compound, General Santos Avenue, Bicutan, Taguig City 1631 Philippines',0,0, 'C');
    // $pdf->Ln(4);
    // $pdf->Cell(20);
    // $pdf->Cell(190,10,'URL: https://ptri.dost.gov.ph/      Facebook@PTRI-DOST      Facebook@TELAPilipinas',0,0, 'C');




    if(!empty($ictclearance_No)){
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
    }
    else{
        //NO ICT CLEARANCE
    }
    
    




  
	$pdf->Output();



?>