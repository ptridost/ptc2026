<?php

require '../vendor/autoload.php';
use Dompdf\Dompdf;

function encodeImageToBase64($logoPath){
    $photoType = pathinfo($logoPath, PATHINFO_EXTENSION);
    $photoData = file_get_contents($logoPath);

    return 'data:image/' . $photoType . ';base64,' . base64_encode($photoData);
}

$rfqID = $_GET['ID'];
$pr_NoQ = $_GET['prNo'];

require_once('../operations/getpreview_Quotation.php');

$html = "
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
        <title>RFQ-PR No. $pr_NoQ</title>

        <style type='text/css'>
            html{
                margin-bottom: 10px;
            }

            body{
                font-family: 'Arial', sans-serif;
                font-size: 8pt;
            }

            table{
                width: 100%;
            }

            #document-title{
                font-size: 7.5pt;
                text-align: right;
            }

            #esig{
                float: right;
                width: 30%;
                text-align: center;
            }

            .collapsed-border{
                border: 1px solid #000;
                border-collapse: collapse;
            }

            .centered{
                text-align: center;
            }

            .indented{
                padding-left: 5%;
            }

            td{
                vertical-align: top;
            }

            .supplierLargeText{
                font-size: larger;
            }


        </style>
    </head>

    <body>
        <div id='document-title'>
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
                    <th style='width: 60%;'></th>
                    <th style='width: 20%;'></th>
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
                    <td><em>Company Name:</em> <b><u>". utf8_encode(strtoupper($rfq_Company)) ."</u></b></td>
                    <td></td>
                    <td>PR No.</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$pr_NoQ</strong></td>
                </tr>

                <tr>
                    <td><em>Address:</em> <b><u>". utf8_encode(strtoupper($rfq_Address)) ."</u></b></td>
                    <td></td>
                    <td>End-user</td>
                    <td style='border-bottom: 1px solid #000;'><strong>$ictclearance_requisitioner</strong></td>
                </tr>
            </tbody>
        </table>

        <div id='rfq-message' class=''>
            <p>
                Please quote your lowest price on the item/s listed below, subject to the General Conditions on the last page, stating the shortest time of delivery and submit
                your quotation duly signed by your representative not later than <u><b>$pr_rfqDuration CALENDAR DAYS</b></u> in the return envelope attached herewith.
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
                    <td>2. <strong>DELIVERY PERIOD MUST BE WITHIN <u><b>$pr_rfqDeliveryPeriod</b></u> CALENDAR DAYS.</strong></td>
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
                    <td>4. <strong>PRICE VALIDITY SHALL BE FOR A PERIOD OF <u><b>$pr_rfqPricePeriod</b></u> CALENDAR DAYS</strong></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>5. <strong>PhilGEPS REGISTRATION CERTIFICATE SHALL BE ATTACHED UPON SUBMISSION OF THE QUOTATION.</strong></td>
                </tr>

                <tr>
                    <td></td>
                    <td>6. <strong>BIDDERS SHALL SUBMIT ORIGINAL BROCHURES SHOWING CERTIFICATIONS OF THE PRODUCT BEING OFFERED.</strong></td>
                </tr>
                <br>
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
    
    for ($i=0; $i < count($rfq_suppPrice); $i++){
        $itemNo = $i+1;
        $html .= "<tr class='collapsed-border'>";
        $html .= "<td class='centered'>". $rfq_itemNo[$i] ."</td>";
        $html .= "<td class='collapsed-border'><strong>".$rfq_suppDesc[$i]."</strong></td>";
        $html .= "<td class='centered collapsed-border'>".$rfq_qty[$i]."</td>";
        $html .= "<td class='centered collapsed-border supplierLargeText'><b>Php ".number_format($rfq_suppPrice[$i], 2)."</b></td>";
        $html .= "</tr>";

    }

$html.= "       
                
            </tbody>
        </table>

        <table>
            <tbody>
                <tr>
                    <td style='width: 7%;'></td>
                    <td style='padding-right: 20px; width: 70%; text-align: right;'>Brand and Model:</td>
                    <td class='centered' style='width: 23%; border-bottom: 1px solid #000;'><b>$rfq_BrandModel</b></td>
                </tr>

                <tr>
                    <td></td>
                    <td style='padding-right: 20px; width: 70%; text-align: right;'>Delivery Period:</td>
                    <td class='centered' style='width: 23%; border-bottom: 1px solid #000;'><b>$rfq_DeliveryPeriod calendar days</b></td>
                </tr>

                <tr>
                    <td></td>
                    <td style='padding-right: 20px; width: 70%; text-align: right;'>Warranty:</td>
                    <td class='centered' style='width: 23%; border-bottom: 1px solid #000;'><b>$rfq_Warranty</b></td>
                </tr>

                <tr>
                    <td></td>
                    <td style='padding-right: 20px; width: 70%; text-align: right;'>Price Validity:</td>
                    <td class='centered' style='width: 23%; border-bottom: 1px solid #000;'><b>$rfq_PriceValidity</b></td>
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
                    <td class='centered' style='width: 23%;'><b>". utf8_encode(strtoupper($rfq_uploadedBy)) ."</b></td>
                </tr>
                <tr>
                    <td style='width: 77%;'></td>
                    <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 5px;'>Printed Name / Signature</td>
                </tr>

                <tr>
                    <td style='width: 77%;'></td>
                    <td class='centered' style='width: 23%;'><b>$rfq_telNum</b></td>
                </tr>
                <tr>
                    <td style='width: 77%;'></td>
                    <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 5px;'>Tel. No. / Cellphone No.</td>
                </tr>

                <tr>
                    <td style='width: 77%;'></td>
                    <td class='centered' style='width: 23%;'><b>$rfq_email</b></td>
                </tr>
                <tr>
                    <td style='width: 77%;'></td>
                    <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 5px;'>E-mail Address</td>
                </tr>

                <tr>
                    <td style='width: 77%;'></td>
                    <td class='centered' style='width: 23%;'><b>$rfq_SubmittedDate</b></td>
                </tr>
                <tr>
                    <td style='width: 77%;'></td>
                    <td style='width: 23%; border-top: 1px solid #000; padding-bottom: 5px;'>Date</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
";

$pdf = new Dompdf();
$pdf->loadHtml($html);
$pdf->setPaper('A4', 'Portrait');
$pdf->render();
$pdf->stream('RFQ-PR-'.$pr_NoQ.'.pdf', array('Attachment'=>0));

?>