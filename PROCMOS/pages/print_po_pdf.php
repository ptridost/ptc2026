<?php

require '../vendor/autoload.php';
use Dompdf\Dompdf;

// Function for base 64 encoding an image to be printed
function encodeImageToBase64($logoPath){
    $photoType = pathinfo($logoPath, PATHINFO_EXTENSION);
    $photoData = file_get_contents($logoPath);

    return 'data:image/' . $photoType . ';base64,' . base64_encode($photoData);
}

if (isset($_GET['ID'])){
    $poID = $_GET['ID'];
}
else{
    // Return to previous page if no URL parameters found.
    echo "<script>alert('No IDs found. Returning to previous page.'); history.back();</script>";
}

require_once("../operations/getPO.php");
// require_once('../operations/getpreview_RFQ.php');

$fontSize = 13;

$htmlToPrint = '
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>PO-'. $po_Number .'</title>

        <style type="text/css">
            html{
                margin-bottom: 10px;
            }

            body{
                font-family: "Arial", sans-serif;
            }

            table{
                width: 100%;
                font-size: 0.8em;   
            }

            #document-title{
                width: 100%;
                height: 85px;
                text-align: center;
            }

            #document-title #left{
                float: left;
                width: 35%;
                text-align: right;
            }

            #document-title #right{
                padding-top: 10px;
                float: right;
                width: 85%;
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

            .sidebordered-content{
                border-right: 1px solid #000;
                border-left: 1px solid #000;
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



$PO_ToPrint = '
    <div id="pr-to-print">
        <div class="document-number">
            FAD-PPGSS Form No. 14 <br/>
            Rev. 1/15-05-17
        </div>

        <div id="document-title">
            

            <div id="centered">
                <strong><label style="">PURCHASE ORDER</label></strong> <br/> 
                
                <strong><label style="font-size: 16px">PHILIPPINE TEXTILE RESEARCH INSTITUTE</label></strong> <br/> 
                <label style="font-size: 16px">Agency / Procuring Entity</label>
            </div>
        </div>
    
        <div id="pr-header">
            <table>
                <tbody>
                    <tr class="padded-content">
                        <td style="width: 16%;"><span>Supplier : </span></td>
                        <td style="width: 60%; border-right: 1px solid #000;"><strong><span>'. $po_Supplier .'</span></strong></td>
                        <td style="width: 8%;">PO No. </td>
                        <td>: '. $po_Number .'</td>
                    </tr>

                    <tr class="padded-content">
                        <td><span>Address : </span></td>
                        <td style="border-right: 1px solid #000;"><span>'. $po_Address .'</span></td>
                        <td>Date </td>
                        <td>: '. $po_DateNew .'</td>
                    </tr>

                    <tr class="padded-content">
                        <td><span>E-mail Add. : </span></td>
                        <td style="border-right: 1px solid #000;"><span><u>'. $po_Email .'</u></span></td>
                        <td>Mode of Procurement </td>
                        <td>: '. $po_mode .'</td>
                    </tr>

                    <tr class="padded-content">
                        <td><span>Tel. No. : </span></td>
                        <td style="border-right: 1px solid #000;"><span>'. $po_Telnum .'</span></td>
                        <td>PR No. </td>
                        <td>: '. $prNumber .' <br> &nbsp;&nbsp;'. $prNo_DateNew .'</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr class="padded-content">
                        <td style="width: 16%;"><span>Gentlemen: </span></td>
                        <td><span>Please furnish this office the following articles subject to the terms and conditions contained herein: </span></td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr class="padded-content">
                        <td style="width: 16%"><span>Place of Delivery: </span></td>
                        <td style="width: 45%;"><span>' .$aoc_placeofdelivery. '</span></td>
                        <td style="width: 15%;"><span>Delivery Term: </span></td>
                        <td><span>' .$aoc_deliveryterm. '</span></td>
                    </tr>
                    <tr class="padded-content">
                        <td><span>Date of Delivery: </span></td>
                        <td><span></span></td>
                        <td><span>Payment Term: </span></td>
                        <td><span>' .$aoc_paymentterm. '</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="pr-body">
            <table style="border: none">
                <thead style="">
                    <tr class="padded-content">
                        <th style="width: 10%;" class="bordered-content">Stock No.</th>
                        <th style="width: 10%;" class="bordered-content">Unit</th>
                        <th colspan="2" style="" class="bordered-content">Item Description</th>
                        <th style="width: 4%;"class="bordered-content">Qty</th>
                        <th style="width: 12%;" class="bordered-content">Unit Cost</th>
                        <th style="width: 12%;" class="bordered-content">Amount</th>
                    </tr>
                </thead>

                <tbody>';

for ($i=0; $i < count($po_Description); $i++){
    if($i==count($po_Description)-1){
        $PO_ToPrint .= "<tr class='padded-content sidebordered-content'>";
        $PO_ToPrint .= "<td class='centered sidebordered-content'></td>";   
        $PO_ToPrint .= "<td class='centered sidebordered-content'>$po_Unit[$i]</td>";   
        $PO_ToPrint .= "<td colspan='2' class='sidebordered-content'>".$po_Description[$i]."<br><br><br><br><br>* ".$po_remarks."</td>";   
        $PO_ToPrint .= "<td class='centered sidebordered-content'>$po_Qty[$i]</td>";

        $PO_ToPrint .= "<td class='text-right sidebordered-content'>". number_format($po_UnitCost[$i], 2) ."</td>";   
        $PO_ToPrint .= "<td class='text-right sidebordered-content'><strong>". number_format($po_totalCost[$i], 2) ."</strong></td>";
        // if($i == 0){
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        // else{
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        $PO_ToPrint .= "</tr>";
    }
    else{
        $PO_ToPrint .= "<tr class='padded-content sidebordered-content'>";
        $PO_ToPrint .= "<td class='centered sidebordered-content'></td>";   
        $PO_ToPrint .= "<td class='centered sidebordered-content'>$po_Unit[$i]</td>";   
        $PO_ToPrint .= "<td colspan='2' class='sidebordered-content'>".$po_Description[$i]."</td>";   
        $PO_ToPrint .= "<td class='centered sidebordered-content'>$po_Qty[$i]</td>";

        $PO_ToPrint .= "<td class='text-right sidebordered-content'>". number_format($po_UnitCost[$i], 2) ."</td>";   
        $PO_ToPrint .= "<td class='text-right sidebordered-content'><strong>". number_format($po_totalCost[$i], 2) ."</strong></td>";
        // if($i == 0){
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        // else{
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        $PO_ToPrint .= "</tr>";
    }
    
}

for ($i=0; $i < 10; $i++){
    if($i==9){
        $PO_ToPrint .= "<tr class='padded-content sidebordered-content'>";
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td colspan='2' class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";

        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";
        // if($i == 0){
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        // else{
        //     $PO_ToPrint .= "<td class='text-right bordered-content'>". number_format($prd_UnitCost[$i], 2) ."</td>";   
        //     $PO_ToPrint .= "<td class='text-right bordered-content'><strong>". number_format($prd_TotalCost[$i], 2) ."</strong></td>";
        // }
        $PO_ToPrint .= "</tr>";
    }
    else{
        $PO_ToPrint .= "<tr class='padded-content sidebordered-content'>";
        $PO_ToPrint .= "<td class='sidebordered-content centered'><span></span></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td colspan='2' class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";

        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";   
        $PO_ToPrint .= "<td class='sidebordered-content centered'></td>";

        $PO_ToPrint .= "</tr>";
    }
    
}

$PO_ToPrint .= '<tr class="padded-content sidebordered-content">
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="" style=""></td>
                        <td class="centered"><strong>' .strtoupper($acct_Name). '</strong><br>' .$acct_Designation. '</td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                    </tr>

                    <tr class="padded-content sidebordered-content" style="border-bottom: 1px solid #000">
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="" style="">Fund source:</td>
                        <td class="centered"><u>' .$purchrequest_charging. '</u></td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                        <td class="sidebordered-content" style=""></td>
                    </tr>

                    <tr class="padded-content sidebordered-content" style="border-bottom: 1px solid #000">
                        <td colspan="2" style="font-size: 0.90em;">(Total Amount in Words)</td>
                        <td colspan="3"><strong>'. $po_TotalAmountinwords .'</strong></td>
                        <td colspan="2" class="text-right"><strong>PhP '. number_format($po_TotalAmount, 2) .'</strong></td>
                    </tr>

                    <tr class="padded-content sidebordered-content">
                        <td colspan="7" style="font-size: 0.90em;">In case of failure to make full delivery within the time specified above, a penalty of one-tenth (1/10) of 1% for every day of delay shall be imposed</td>
                    </tr>

                    <tr class="padded-content sidebordered-content">
                        <td colspan="7" style="font-size: 0.90em;"></td>
                    </tr>

                    <tr class="padded-content sidebordered-content">
                        <td colspan="7" style="font-size: 0.90em;"></td>
                    </tr>

                    <tr class="padded-content sidebordered-content">
                        <td colspan="7" style="font-size: 0.90em;"></td>
                    </tr>';


$PO_ToPrint .= "</tbody>

            </table>
        </div> <!-- end of PR body --!>
    </div>";


$PO_ToPrint .= '<div id="pr-body">
            

            <table style="border: none">
                <tbody>
                    <tr class="padded-content sidebordered-content">
                        <td class="" style="width: 10%;"><span>Conforme: </span></td>
                        <td style="width: 50%"><u>' .utf8_encode(strtoupper($supplierContactPerson)). ' (SGD)</u><br><span style="text-align: center">Signature over printed name of Supplier</span></td>
                        <td class="centered"><strong>' .utf8_encode(strtoupper($appr_Name)). '</strong><br>' .$appr_Designation. '</td>
                    </tr>

                    <tr class="padded-content sidebordered-content">
                        <td class="" style="width: 10%;"><span></span></td>
                        <td class="" style=""><b><u>'. $po_conformedateNew .'</u></b></td>
                        <td class="centered" style="width: 40%">___________________</td>
                    </tr>
                    <tr class="padded-content sidebordered-content" style="border-bottom: 1px solid #000">
                        <td class="" style="width: 10%;"><span></span></td>
                        <td class="" style="">Date</td>
                        <td class="centered" style="width: 40%">Date</td>
                    </tr>

                </tbody>
            </table>

            <table style="border: none">
                <tbody>
                    <tr class="padded-content sidebordered-content">
                        <td class="sidebordered-content" style="width: 65%;"><span>Funds Available: </span></td>
                        <td class="">Oblig No. ________________________</td>
                    </tr>
                    <tr class="padded-content sidebordered-content">
                        <td class="sidebordered-content" style="width: 65%;"></td>
                        <td class="">Amount __________________________</td>
                    </tr>
                   
                </tbody>
            </table>

            <table style="border: none">
                <tbody>
                    <tr class="padded-content sidebordered-content">
                        <td class="" style="width: 10%;"></td>
                        <td class="" style="width: 55%; border-right: 1px solid #000"><strong>' .utf8_encode(strtoupper($acctg_Name)). '</strong><br><span style="text-align: center">' .$acctg_Designation. '</span></td>
                        <td></td>
                    </tr>
                    <tr class="padded-content sidebordered-content" style="border-bottom: 1px solid #000">
                        <td class="" style="width: 10%;"></td>
                        <td style="width: 55%; border-right: 1px solid #000"></td>
                        <td></td>
                    </tr>


                </tbody>
            </table>
        </div>


        


';





$lastTagsToPrint .= '</div>


<style>
#tnc-container *{
 padding:10px !important;
 margin:5px !important;
 
}
 #tnc-container{
 page-break-before:always;
}
#instructions{
border:1px solid black;
font-size:12px;
padding:4px;
margin:0px;
}
#tnc-container li{
margin:'.$fontSize.'px; 
text-align:justify;
line-height:16px;
text-spacing:10px;
}
</style>
<div style="font-family:\'Arial\',sans-serif;" id="tnc-container">

<div >
<p style="text-align:center; font-weight:bold;">
TERMS AND CONDITIONS
</p>
<ol style="font-size:'.$fontSize.'px;">

<li>
ALL PRICES QUOTED HEREIN ARE VALID, BINDING AND EFFECTIVE AT LEAST WITHIN THIRTY (30) CALENDAR DAYS FROM THE DATE OF QUOTATION.
</li>
<li>
AWARDEE shall be responsible for the source(s) of his supplies/materials/equipment shall make deliveries in accordance with schedule, quality and specifications of the award and purchase order. Failure by the AWARDEE to comply with the same shall be ground for cancellation of the award and purchase order issued to the AWARDEE and for rewarding the item(s) to the ALTERNATE AWARDEE.
</li>
<li>
AWARDEE shall pick up purchase order(s) issued in his favor within three (3) days after receipt of notice to that effect. A telephone call or fax transmission shall constitute an official notice to the AWARDEE. Thereafter, if the purchase order(s) remain unclaimed, the said purchase order(s) shall be sent by messengerial service to the AWARDEE at the latter\'s expense. To avoid delay in the delivery of the requesting agency\'s requirement, all DEFAULTING AWARDEE shall be precluded from proposing or submitting a substitute sample.
</li>
<li>
Subject to the provisions of the preceding paragraph, where AWARDEE has accepted a purchase order but fails to deliver the required product(s) within the time called for in the same order, he shall be extended a maximum of fifteen (15) calendar days under liquidated damages to make good his delivery. Thereafter, if AWARDEE has not completed delivery within the extended period, the subject purchase order shall be cancelled and the award for the undelivered balance withdrawn for the from that AWARDEE. The Procurement Service shall then purchase the required item(s) from such other source(s) as it may determine, with the difference in price to be charged against DEFAULTING AWARDEE Refusal by the DEFAULTING AWARDEE to shoulder the price difference shall ground for his disqualification from future bids of the same or items without prejudice to the imposition of other sanctions as prescribed under RA 9184 and its IRR-A.
</li>
<li>
All deliveries by suppliers shall be subject to inspection and acceptance by the PTRI. All necessary laboratory texts undertaken by the PTRI on the item(s) shall be for the account of the supplier.
</li>
<li>
Rejected deliveries shall be construed as non-delivery of product(s)/item(s) so ordered and shall be subject to liquidated damages, subject to the terms and conditions prescribed under paragraph 4 hereof.
</li>
<li>
Supplier shall guarantee his deliveries to be free from defects. Any defective item(s)/product(s), therefore that may be discovered by the PTRI within three (30) months after acceptance of the same, shall be replaced by the supplier with seven (7) calendar days upon receipt of a written notice to that affect.</li>
<li>
A penalty of one-tenth of one percent (0.001) of the total value of the product(s)/goods purchased shall be deducted for each day of delay in the delivery of the product(s)/goods ordered.</li>
<li>
All duties, excise and other taxes and revenue charges, if any, shall be for the supplier\'s account.</li>
<li>
As a pre-condition to payment, IMPORTATION DOCUMENTS specifically showing the condition and serial numbers of the imported equipment purchased should be submitted by the supplier to the PTRI.
</li>
<li>
All transactions are subject to withholding of creditable Value Added Tax (VAT) per Revenue Regulations No. 10-93.
</li>
</ol>
</div>

<div id="instructions">Instructions to the User:</div>

</div>

</body></html>';



$dompdf = new Dompdf();

// var_dump(count($ictdetails_description));
//     die();

// if(!empty($ictclearance_ID)){
    

//     if($Quotation_numcrn != "Not Yet Assigned"){
//         $dompdf->loadHtml($htmlToPrint . $PO_ToPrint . "<div style='page-break-before: always;'></div>" . $ictClearanceToPrint . "<div style='page-break-before: always;'></div>" . $rfqToPrint . $lastTagsToPrint);

//     }
//     else{
//         $dompdf->loadHtml($htmlToPrint . $PO_ToPrint . "<div style='page-break-before: always;'></div>" . $ictClearanceToPrint . $lastTagsToPrint);

//     }
// }
// else{
//     // var_dump("while");
//     // die();

//     // if(!empty($Quotation_numcrn)){
//     if($Quotation_numcrn != "Not Yet Assigned"){

//         $dompdf->loadHtml($htmlToPrint . $PO_ToPrint . "<div style='page-break-before: always;'></div>" . $rfqToPrint . $lastTagsToPrint);
//     }
//     else{
//         $dompdf->loadHtml($htmlToPrint . $PO_ToPrint . $lastTagsToPrint);
//     }
// }

$dompdf->loadHtml($htmlToPrint . $PO_ToPrint . $lastTagsToPrint);

$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('PO-'. $po_Number . '.pdf', array('Attachment'=>0));

?>
