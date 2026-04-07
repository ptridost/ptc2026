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
    $ictclearance_ID = $_GET['ID'];
}
else{
    // Return to previous page if no URL parameters found.
    echo "<script>alert('No ID found. Returning to previous page.'); history.back();</script>";
}

require_once("../operations/get_ICTClearance.php");

$htmlToPrint = '
<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>ICT-'. $ictclearance_No .'</title>

        <style type="text/css">
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

            .bordered-content{
                border: 1px solid #000;
            }

            td{
                vertical-align: top;
            }
        </style>
    </head>
    <body>';


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

// <tr>
//             <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'><img src='". encodeImageToBase64('../photos/'. $ictclearance_reviewedesig) ."' width='80' height='30' /></td>
//         </tr>
if(empty($ictclearance_approvedesig)){
    $ictClearanceToPrint .= "
        <tr style='border-top: 1px solid #000;'>
            <td colspan='2' style=' border-right: 1px solid #000;'>Reviewed by:</td>
            <td colspan='2'>Approved by:</td>
        </tr>

        <tr>
            <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'></td>
            <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'></td>
        </tr>

        <tr>
            <td colspan='2' class='centered' style='border-right: 1px solid #000;'><strong style='padding: 0 50px; border-top: 2px solid #000; '>PICTS Staff</strong></td>
            <td colspan='2' class='centered'><strong style='padding: 0 50px; border-top: 2px solid #000;'>Head PICTS</strong></td>
        </tr>
    
    </tbody>
            </table>
        </div>
    </div>";
}

// <tr>
//             <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'><img src='". encodeImageToBase64('../photos/'. $ictclearance_reviewedesig) ."' width='80' height='30' /></td>
//             <td colspan='2' class='centered' style='padding: 7px;'><img src='". encodeImageToBase64('../photos/'. $ictclearance_approvedesig) ."' width='80' height='30' /></td>
//         </tr>
else{
    $ictClearanceToPrint .= "
        <tr style='border-top: 1px solid #000;'>
            <td colspan='2' style=' border-right: 1px solid #000;'>Reviewed by:</td>
            <td colspan='2'>Approved by:</td>
        </tr>

        <tr>
            <td colspan='2' class='centered' style='padding: 7px; border-right: 1px solid #000;'></td>
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
}                


$lastTagsToPrint .= '</body></html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($htmlToPrint . $ictClearanceToPrint . $lastTagsToPrint);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('ICT-Clearance-'. $ictclearance_No . '.pdf', array('Attachment'=>0));

?>