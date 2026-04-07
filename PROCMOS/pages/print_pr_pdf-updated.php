<?php

require '../vendor/autoload.php';
use Dompdf\Dompdf;

// Function for base 64 encoding an image to be printed
function encodeImageToBase64($logoPath){
    $photoType = pathinfo($logoPath, PATHINFO_EXTENSION);
    $photoData = file_get_contents($logoPath);

    return 'data:image/' . $photoType . ';base64,' . base64_encode($photoData);
}

if (isset($_GET['ID']) && isset($_GET['acctID'])){
    $purchaserequestID = $_GET['ID'];
    $acctID = $_GET['acctID'];
}
else{
    // Return to previous page if no URL parameters found.
    echo "<script>alert('No IDs found. Returning to previous page.'); history.back();</script>";
}

require_once("../operations/getPR.php");
// require_once('../operations/getpreview_RFQ.php');



$htmlToPrint = '
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PR-'. $purchrequest_No .'</title>

        <style type="text/css">
            @page{
                margin: 5%
            }

            body{
                height: 100%;
                font-family: "Arial", sans-serif;
            }

            table{
                width: 100%;
                font-size: 0.8em;   
            }

            #document-title{
            margin-top: -10px;
                width: 100%;
                height: 85px;
                text-align: center;
            }

            #document-title #left{
                float: left;
                width: 20%;
                text-align: right;
            }

            #document-title #right{
                padding-top: 10px;
                float: right;
                width: 95%;
                text-align: center;
            }
            
            #pr-number{
                font-family: "DejaVu Sans Mono", monospace;
                color: #7c3eb3;
                font-size: 1.5em;
            }

            #pr-date{
                font-family: "DejaVu Sans Mono", monospace;
                color: #7c3eb3;
                text-transform: uppercase;
            }

            #pr-header table{
                border: 1px solid #000;
                border-bottom: none;
                border-collapse: collapse;
            }

            #pr-body table{
                border: 1px solid #000;
                border-collapse: collapse;
                max-height: 80%;
            }

            #ict-clearance table{
                border: 1px solid #000;
                border-collapse: collapse;
            }

            .document-number{
                font-size: 0.7em;
                text-align: right;
                opacity: 0.7;
            }

            .centered{
                text-align: center;
            }

            .padded-content th, .padded-content td{
                padding: 5px;
            }

            #ict-clearance-body td{
                padding: 0 5px;
            }

            #rfq{
                font-size: 9pt;
            }

            #esig{
                float: right;
                width: 30%;
                text-align: center;
            }

            .bordered-content{
                border: 1px solid #000;
            }

            .centered{
                text-align: center;
            }

            .text-right{
                text-align: right;
            }

            .padded-content th, .padded-content td{
                padding: 5px;
            }

            .collapsed-border{
                border: 1px solid #000;
                border-collapse: collapse;
            }

            .indented{
                padding-left: 5%;
            }

            td{
                vertical-align: top;
            }
        </style>
    </head>
    <body>';



$PRToPrint = '
    <div id="pr-to-print">
        <div class="document-number">
            FAD-PPGSS Form No. 15 <br/>
            Rev. 1/15-05-17
        </div>

        <div id="document-title">
            <div id="left">
                <img src="'. encodeImageToBase64('../photos/ptrionlywhite.png') .'" width="60" height="60" />
            </div>

            <div id="right">
                <strong><label style="">PURCHASE REQUEST</label></strong> <br/> 
                
                <label style="font-size: 16px">Philippine Textile Research Institute</label>
            </div>
        </div>
    
        <div id="pr-header">
            <table>
                <tbody>
                    <tr class="padded-content">
                        <td style="border-right: 1px solid #000;"><span><strong>Division: </strong></span><span>'. $acct_DivisionEnd .'</span></td>
                        <td><small><strong>P.R. No. </strong><span id="pr-number">'. $purchrequest_No .'</span></small></td>
                        <td>dtd. <span id="pr-date">'. $prNo_dtdNew .'</span></td>
                    </tr>

                    <tr class="padded-content">
                        <td style="border-right: 1px solid #000;"><span><strong>Section: </strong></span>'. $acct_SectionEnd .'</td>
                        <td><small><strong>SAI No.</strong></small></td>
                        <td>dtd.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="pr-body">
            <table>
                <thead style="border-bottom: 1px solid #000;">
                    <tr class="padded-content">
                        <th style="width: 10%;" class="bordered-content">Stock No.</th>
                        <th style="width: 10%;" class="bordered-content">Unit</th>
                        <th style="width: 46%;" class="bordered-content">Item Description</th>
                        <th style="width: 4%;"class="bordered-content">Qty</th>
                        <th style="width: 15%;" class="bordered-content">Unit Cost</th>
                        <th style="width: 15%;" class="bordered-content">Total Cost</th>
                    </tr>
                </thead>

                <tbody>';

for ($i=0; $i < count($prd_Description); $i++){
    $PRToPrint .= "<tr class='padded-content bordered-content'>";
    $PRToPrint .= "<td class='centered bordered-content'>$prd_Stock[$i]</td>";   
    $PRToPrint .= "<td class='centered bordered-content'>$prd_Unit[$i]</td>";   
    $PRToPrint .= "<td class='bordered-content'>".$prd_Description[$i]."</td>";   
    $PRToPrint .= "<td class='centered bordered-content'>$prd_Qty[$i]</td>";

    $PRToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
    $PRToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
    // if($i == 0){
    //     $PRToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
    //     $PRToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
    // }
    // else{
    //     $PRToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
    //     $PRToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
    // }
    $PRToPrint .= "</tr>";
} 

if(empty($pr_requestoresig)){
    if(empty($pr_approveesig)){
        $PRToPrint .="
                
                
                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Charging:</strong></td>
                    <td colspan='3' style='border-right: 1px solid #000;'>$purchrequest_charging</td>
                    <td class='text-right'><strong>PhP ". number_format($purchrequest_totalfee, 2) ."</strong></td>
                </tr>

                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Purpose:</strong></td>
                    <td colspan='4'>$purchrequest_purpose</td>
                </tr>

                <tr class='padded-content'>
                    <td colspan='2' style='border-right: 1px solid #000;'></td>
                    <td style='border-right: 1px solid #000;'>Requested by:</td>
                    <td colspan='3'>Approved by:</td>
                </tr>

                <tr>
                    <td colspan='2' style='vertical-align: bottom; border-right: 1px solid #000;'>Signature</td>

                    <td class='centered' style='border-right: 1px solid #000;'></td>
                    <td class='centered' colspan='3'></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Printed Name</td>
                    <td class='centered' style='border-right: 1px solid #000;'><strong><u>". utf8_encode(strtoupper($req_Name)) ."</u></strong></td>
                    <td class='centered' colspan='3' style='text-transform: uppercase;'><strong><u>". utf8_encode(strtoupper($appr_Name)) ."</u></strong></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Position</td>
                    <td class='centered' style='border-right: 1px solid #000;'>$req_Designation</td>
                    <td class='centered' colspan='3'>$appr_Designation</td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Date</td>
                    <td class='centered' style='border-right: 1px solid #000;'>". $date_prrequestoresigNew ."</td>
                    <td class='centered' colspan='3'></td>
                </tr>
            </tbody>

            </table>
        </div> <!-- end of PR body --!>
    </div>";
    }
    else{
        $PRToPrint .="
                
                
                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Charging:</strong></td>
                    <td colspan='3' style='border-right: 1px solid #000;'>$purchrequest_charging</td>
                    <td class='text-right'><strong>PhP ". number_format($purchrequest_totalfee, 2) ."</strong></td>
                </tr>

                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Purpose:</strong></td>
                    <td colspan='4'>$purchrequest_purpose</td>
                </tr>

                <tr class='padded-content'>
                    <td colspan='2' style='border-right: 1px solid #000;'></td>
                    <td style='border-right: 1px solid #000;'>Requested by:</td>
                    <td colspan='3'>Approved by:</td>
                </tr>

                <tr>
                    <td colspan='2' style='vertical-align: bottom; border-right: 1px solid #000;'>Signature</td>

                    <td class='centered' style='border-right: 1px solid #000;'></td>
    
                    <td class='centered' colspan='3'><img src='". encodeImageToBase64('../photos/'.$pr_approveesig) ."' width='150' height='50'/ ></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Printed Name</td>
                    <td class='centered' style='border-right: 1px solid #000;'><strong><u>". utf8_encode(strtoupper($req_Name)) ."</u></strong></td>
                    <td class='centered' colspan='3' style='text-transform: uppercase;'><strong><u>". utf8_encode(strtoupper($appr_Name)) ."</u></strong></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Position</td>
                    <td class='centered' style='border-right: 1px solid #000;'>$req_Designation</td>
                    <td class='centered' colspan='3'>$appr_Designation</td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Date</td>
                    <td class='centered' style='border-right: 1px solid #000;'>". $date_prrequestoresigNew ."</td>
                    <td class='centered' colspan='3'></td>
                </tr>
            </tbody>

            </table>
        </div> <!-- end of PR body --!>
    </div>";
    }
    // $PRToPrint .="
                
                
    //             <tr class='padded-content' style='border-bottom: 1px solid #000;'>
    //                 <td colspan='2'><strong>Charging:</strong></td>
    //                 <td colspan='3' style='border-right: 1px solid #000;'>$purchrequest_charging</td>
    //                 <td class='centered'><strong>PhP ". number_format($purchrequest_totalfee, 2) ."</strong></td>
    //             </tr>

    //             <tr class='padded-content' style='border-bottom: 1px solid #000;'>
    //                 <td colspan='2'><strong>Purpose:</strong></td>
    //                 <td colspan='4'>$purchrequest_purpose</td>
    //             </tr>

    //             <tr class='padded-content'>
    //                 <td colspan='2' style='border-right: 1px solid #000;'></td>
    //                 <td style='border-right: 1px solid #000;'>Requested by:</td>
    //                 <td colspan='3'>Approved by:</td>
    //             </tr>

    //             <tr>
    //                 <td colspan='2' style='vertical-align: bottom; border-right: 1px solid #000;'>Signature</td>

    //                 <td class='centered' style='border-right: 1px solid #000;'></td>
    //                 <td class='centered' colspan='3'></td>
    //             </tr>

    //             <tr>
    //                 <td colspan='2' style='border-right: 1px solid #000;'>Printed Name</td>
    //                 <td class='centered' style='border-right: 1px solid #000;'><strong><u>". utf8_encode(strtoupper($req_Name)) ."</u></strong></td>
    //                 <td class='centered' colspan='3' style='text-transform: uppercase;'><strong><u>". utf8_encode(strtoupper($appr_Name)) ."</u></strong></td>
    //             </tr>

    //             <tr>
    //                 <td colspan='2' style='border-right: 1px solid #000;'>Position</td>
    //                 <td class='centered' style='border-right: 1px solid #000;'>$req_Designation</td>
    //                 <td class='centered' colspan='3'>$appr_Designation</td>
    //             </tr>

    //             <tr>
    //                 <td colspan='2' style='border-right: 1px solid #000;'>Date</td>
    //                 <td class='centered' style='border-right: 1px solid #000;'>". $date_prrequestoresigNew ."</td>
    //                 <td class='centered' colspan='3'></td>
    //             </tr>
    //         </tbody>

    //         </table>
    //     </div> <!-- end of PR body --!>
    // </div>";
}
else{
    if(empty($pr_approveesig)){
        $PRToPrint .="
                
                
                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Charging:</strong></td>
                    <td colspan='3' style='border-right: 1px solid #000;'>$purchrequest_charging</td>
                    <td class='text-right'><strong>PhP ". number_format($purchrequest_totalfee, 2) ."</strong></td>
                </tr>

                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Purpose:</strong></td>
                    <td colspan='4'>$purchrequest_purpose</td>
                </tr>

                <tr class='padded-content'>
                    <td colspan='2' style='border-right: 1px solid #000;'></td>
                    <td style='border-right: 1px solid #000;'>Requested by:</td>
                    <td colspan='3'>Approved by:</td>
                </tr>

                <tr>
                    <td colspan='2' style='vertical-align: bottom; border-right: 1px solid #000;'>Signature</td>

                    <td class='centered' style='border-right: 1px solid #000;'><img src='". encodeImageToBase64('../photos/'.$pr_requestoresig) ."' width='70' height='25' /></td>
                    <td class='centered' colspan='3'></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Printed Name</td>
                    <td class='centered' style='border-right: 1px solid #000;'><strong><u>". utf8_encode(strtoupper($req_Name)) ."</u></strong></td>
                    <td class='centered' colspan='3' style='text-transform: uppercase;'><strong><u>". utf8_encode(strtoupper($appr_Name)) ."</u></strong></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Position</td>
                    <td class='centered' style='border-right: 1px solid #000;'>$req_Designation</td>
                    <td class='centered' colspan='3'>$appr_Designation</td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Date</td>
                    <td class='centered' style='border-right: 1px solid #000;'>". $date_prrequestoresigNew ."</td>
                    <td class='centered' colspan='3'></td>
                </tr>
            </tbody>

            </table>
        </div> <!-- end of PR body --!>
    </div>";
    }
    else{
        $PRToPrint .="
                
                
                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Charging:</strong></td>
                    <td colspan='3' style='border-right: 1px solid #000;'>$purchrequest_charging</td>
                    <td class='text-right'><strong>PhP ". number_format($purchrequest_totalfee, 2) ."</strong></td>
                </tr>

                <tr class='padded-content' style='border-bottom: 1px solid #000;'>
                    <td colspan='2'><strong>Purpose:</strong></td>
                    <td colspan='4'>$purchrequest_purpose</td>
                </tr>

                <tr class='padded-content'>
                    <td colspan='2' style='border-right: 1px solid #000;'></td>
                    <td style='border-right: 1px solid #000;'>Requested by:</td>
                    <td colspan='3'>Approved by:</td>
                </tr>

                <tr>
                    <td colspan='2' style='vertical-align: bottom; border-right: 1px solid #000;'>Signature</td>

                    <td class='centered' style='border-right: 1px solid #000;'><img src='". encodeImageToBase64('../photos/'.$pr_requestoresig) ."' width='70' height='25' /></td>
                    <td class='centered' colspan='3'><img src='". encodeImageToBase64('../photos/'.$pr_approveesig) ."' width='150' height='50'/ ></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Printed Name</td>
                    <td class='centered' style='border-right: 1px solid #000;'><strong><u>". utf8_encode(strtoupper($req_Name)) ."</u></strong></td>
                    <td class='centered' colspan='3' style='text-transform: uppercase;'><strong><u>". utf8_encode(strtoupper($appr_Name)) ."</u></strong></td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Position</td>
                    <td class='centered' style='border-right: 1px solid #000;'>$req_Designation</td>
                    <td class='centered' colspan='3'>$appr_Designation</td>
                </tr>

                <tr>
                    <td colspan='2' style='border-right: 1px solid #000;'>Date</td>
                    <td class='centered' style='border-right: 1px solid #000;'>". $date_prrequestoresigNew ."</td>
                    <td class='centered' colspan='3'></td>
                </tr>
            </tbody>

            </table>
        </div> <!-- end of PR body --!>
    </div>";
    }
}



$ictClearanceToPrint = "
    <div id='ict-clearance'>
        <div class='document-number'>
            PICTS Form No. 002 <br/>
            Rev. 1/12-05-17
        </div>

        <div class='centered'>
            <strong>PHILIPPINE TEXTILE RESEARCH INSTITUTE</strong><br/>
            <small>Planning and ICT Staff</small>

            <h3>ICT PURCHASE CLEARANCE SLIP</h3>
        </div>

        <div id='ict-clearance-body'>
            <table>
                <tbody>
                    <tr>
                        <td style='width: 15%; border-right: 1px solid #000;'>Requisitioner:</td>
                        <td rowspan='2' class='centered' style='width: 35%; border-right: 1px solid #000;'>$ictclearance_requisitioner</td>
                        <td style='width: 25%;border-right: 1px solid #000;'>Date:</td>
                        <td style='width: 25%;border-right: 1px solid #000;'><em>ICT Clearance No.:</em></td>
                    </tr>

                    <tr style='border-bottom: 1px solid #000;'>
                        <td style='border-right: 1px solid #000;'></td>
                        <td style='border-right: 1px solid #000;' class='centered'>". date_format(date_create($ictclearance_date), "d-M-Y") ."</td>
                        <td style='border-right: 1px solid #000;' class='centered'>$ictclearance_No</td>
                    </tr>

                    <tr>
                        <td class='centered' style='border-right: 1px solid #000;'>Quantity</td>
                        <td class='centered' style='border-right: 1px solid #000;'>Particular</td>
                        <td colspan='2'>Remarks:</td>
                    <tr/>";
        
for ($i=0; $i < count($ictdetails_description); $i++){
    $ictClearanceToPrint .= "<tr style='border-top: 1px solid #000;'>";
    $ictClearanceToPrint .= "<td style='padding: 3px; border-right: 1px solid #000;' class='centered'>$ictdetails_qty[$i]</td>";
    $ictClearanceToPrint .= "<td style='padding: 3px; border-right: 1px solid #000;'>$ictdetails_description[$i]</td>";

    if ($i == 0){
        $ictClearanceToPrint .= "<td rowspan='". count($ictdetails_description) ."' colspan='2'>$ictclearance_remarks</td>";
    }
    
    $ictClearanceToPrint .= "</tr>";
}
                
$ictClearanceToPrint .= "
        <tr style='border-top: 1px solid #000;'>
            <td colspan='2' style=' border-right: 1px solid #000;'>Reviewed by:</td>
            <td colspan='2'>Approved by:</td>
        </tr>

        <tr>
            <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'><img src='". encodeImageToBase64('../photos/'. $ictclearance_reviewedesig) ."' width='80' height='30' /></td>
            <td colspan='2' class='centered' style='padding: 7px;'><img src='". encodeImageToBase64('../photos/'. $ictclearance_approvedesig) ."' width='80' height='30' /></td>
        </tr>

        <tr>
            <td colspan='2' class='centered' style='border-right: 1px solid #000;'><strong style='padding: 0 50px; border-top: 2px solid #000; '>PICTS Staff</strong></td>
            <td colspan='2' class='centered'><strong style='padding: 0 50px; border-top: 2px solid #000;'>Head PICTS</strong></td>
        </tr>
    
    </tbody>
            </table>
        </div>
    </div>";

$rfqToPrint = "
    <div id='rfq'>
        <div class='document-number'>
            <small>
                FAD-PPGSS Form No. 18 <br/>Rev. 02/15-05-17
            </small>
        </div>

        <div class='centered'>
            <strong><em>PHILIPPINE TEXTILE RESEARCH INSTITUTE</em></strong><br/>
            <em>General Santos Avenue, Bicutan, Taguig City</em><br/>
            <em>Telephone No. 8372071 Local 2373 & 2366; Telefax No. 8370744</em><br/><br/>
            <strong><em>Request for Quotation</em></strong>
        </div>

        <table>
            <thead>
                <tr>
                    <th style='width: 45%;'></th>
                    <th style='width: 25%;'></th>
                    <th style='width: 15%;'></th>
                    <th style='width: 15%;'></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Date</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$dateQuotation</strong></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>Quotation No.</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$Quotation_numcrn</strong></td>
                </tr>

                <tr>
                    <td><em>Company Name:</em> _____________________________</td>
                    <td></td>
                    <td>PR No.</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$purchrequest_No</strong></td>
                </tr>

                <tr>
                    <td><em>Address:</em> ___________________________________</td>
                    <td></td>
                    <td>End-user</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$ictclearance_requisitioner</strong></td>
                </tr>
            </tbody>
        </table>

        <div id='rfq-message' class='centered'>
            <p>
                Please quote your lowest price on the item/s listed below, subject to the General Conditions on the last page, stating the shortest time of delivery and submit
                your quotation duly signed by your representative not later than _________________________ in the return envelope attached herewith.
            </p>

            <p id='esig'>
                <img src='". encodeImageToBase64('../photos/'.$ppgss_esig) ."' width='80' height='30' /> <br/>
                <strong><u>MARILOU G. AGUSTIN</u></strong><br/>
                <label></lable>Administrative Officer V</label>
            </p>
        </div>

        <table style='margin-top: 90px;'>
            <tbody>
                <tr>
                    <td style='width:10%;'>NOTE:</td>
                    <td>1. <strong>ALL ENTRIES MUST BE TYPEWRITTEN.</strong></td>
                </tr>

                <tr>
                    <td></td>
                    <td>2. <strong>DELIVERY PERIOD MUST BE WITHIN _______________ CALENDAR DAYS.</strong></td>
                </tr>
        
                <tr>
                    <td></td>
                    <td>
                        3. <strong>WARRANTY SHALL BE FOR A PERIOD OF, FROM THE ACCEPTANCE BY THE PTRI: <br/>
                        <span class='indented'>a. ONE (1) YEAR FOR EQUIPMENT</span><br/>
                        <span class='indented'>b. SIX (6) MONTHS FOR SUPPLIES & MATERIALS</span>
                    </strong></td>
                </tr>

                <tr>
                    <td></td>
                    <td>4. <strong>PRICE VALIDITY SHALL BE FOR A PERIOD OF _______________ CALENDAR DAYS</strong></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>5. <strong>PhilGEPS REGISTRATION CERTIFICATE SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</strong></td>
                </tr>

                <tr>
                    <td></td>
                    <td>6. <strong>BIDDERS SHALL SUBMIT ORIGINAL BROCHURES SHOWING CERTIFICATIONS OF THE PRODUCT BEING OFFERED.</strong></td>
                </tr>
            </tbody>
        </table>

        <table class='collapsed-border'>
            <thead class='collapsed-border'>
                <tr class='collapsed-border'>
                    <th class='collapsed-border' style='width: 7%;'>ITEM NO.</th>
                    <th class='collapsed-border' style='width: 70%;'>ITEM AND DESCRIPTION</th>
                    <th class='collapsed-border' style='width: 8%;'>QTY</th>
                    <th class='collapsed-border' style='width: 15%;'>UNIT PRICE</th>
                </tr>
            </thead>

            <tbody class='collapsed-border'>";

    
    for ($i=0; $i < count($RFQictdetails_description); $i++){
        $numberOfItems = $i+1;
        $rfqToPrint .= "<tr class='collapsed-border'>";
        $rfqToPrint .= "<td class='centered'>". $numberOfItems ."</td>";
        $rfqToPrint .= "<td class='collapsed-border'><strong>".$RFQictdetails_description[$i]."</strong></td>";
        if(empty($ictdetails_qtyrfq)){
            $rfqToPrint .= "<td class='centered collapsed-border'>".$prd_qty[$i]." ".$prdUnit[$i]."</td>";
        }
        else{
            $rfqToPrint .= "<td class='centered collapsed-border'>".$ictdetails_qtyrfq[$i]."</td>";
        }
        $rfqToPrint .= "<td class='collapsed-border'></td>";
        $rfqToPrint .= "</tr>";
    }

$rfqToPrint .= "
    </tbody>
</table>
<table>
<tbody>
    <tr>
        <td style='width: 7%;'></td>
        <td style='padding-right: 20px; width: 70%; text-align: right;'>Brand and Model:</td>
        <td style='width: 23%; border-bottom: 1px solid #000;'></td>
    </tr>

    <tr>
        <td></td>
        <td style='padding-right: 20px; width: 70%; text-align: right;'>Delivery Period:</td>
        <td style='width: 23%; border-bottom: 1px solid #000;'></td>
    </tr>

    <tr>
        <td></td>
        <td style='padding-right: 20px; width: 70%; text-align: right;'>Warranty:</td>
        <td style='width: 23%; border-bottom: 1px solid #000;'></td>
    </tr>

    <tr>
        <td></td>
        <td style='padding-right: 20px; width: 70%; text-align: right;'>Price Validity:</td>
        <td style='width: 23%; border-bottom: 1px solid #000;'></td>
    </tr>
</tbody>
</table>

<p class='centered'>
After having carefully read and accepted your General Conditions, I/We quote you on the item at prices noted above.
</p>

<table style='margin-top: 30px;'>
<tbody>
    <tr>
        <td style='width: 77%;'></td>
        <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 30px;'>Printed Name / Signature</td>
    </tr>

    <tr>
        <td style='width: 77%;'></td>
        <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 30px;'>Tel. No. / Cellphone No.</td>
    </tr>

    <tr>
        <td style='width: 77%;'></td>
        <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 30px;'>E-mail Address</td>
    </tr>

    <tr>
        <td style='width: 77%;'></td>
        <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 30px;'>Date</td>
    </tr>
</tbody>
</table>
";

$lastTagsToPrint .= '</div></body></html>';

$dompdf = new Dompdf();

// var_dump(count($ictdetails_description));
//     die();

if(!empty($ictclearance_ID)){
    

    if($Quotation_numcrn != "Not Yet Assigned"){
        $dompdf->loadHtml($htmlToPrint . $PRToPrint . "<div style='page-break-before: always;'></div>" . $ictClearanceToPrint . "<div style='page-break-before: always;'></div>" . $rfqToPrint . $lastTagsToPrint);

    }
    else{
        $dompdf->loadHtml($htmlToPrint . $PRToPrint . "<div style='page-break-before: always;'></div>" . $ictClearanceToPrint . $lastTagsToPrint);

    }
}
else{
    // var_dump("while");
    // die();

    // if(!empty($Quotation_numcrn)){
    if($Quotation_numcrn != "Not Yet Assigned"){

        $dompdf->loadHtml($htmlToPrint . $PRToPrint . "<div style='page-break-before: always;'></div>" . $rfqToPrint . $lastTagsToPrint);
    }
    else{
        $dompdf->loadHtml($htmlToPrint . $PRToPrint . $lastTagsToPrint);
    }
}



$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('PR-'. $purchrequest_No . '.pdf', array('Attachment'=>0));

?>