<?php

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize DOMPDF with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

if (isset($_GET['ID']) && isset($_GET['prNo'])){
    $aocID = $_GET['ID'];
    $prNo = $_GET['prNo'];

    require_once("../operations/getAOC.php");
}
else{
    // Return to previous page if no URL parameters found.
    echo "<script>alert('No IDs found. Returning to previous page.'); history.back();</script>";
}

// // Test Data for the document
// $data = [
//     'id' => '25-018A dtd',
//     'date' => '01/29/25',
//     'pr_no' => '2501-0053',
//     'pr_no_date' => '01/30/25',
//     'QN' => '0046-25',
//     'QN_date' => '01/23/25',
//     'particulars' => 'HP131A Toner Cartridge (Black, Cyan, Magenta, and Yellow)<br>
//                      VAT Inclusive <br>
//                      Inclusive of delivery to DOST-PTRI, Gen. Santos Ave., Bicutan, Taguig City <br>
//                      Delivery period: 10 calendar days upon receipt of PO',
//     'item_no' => '1',    
//     'quantity' => '1',
//     'unit' => 'lot',
//     'Supplier' => [
//         ['name' => 'Group 5 Computer Products & Suppliers, Inc.', 'description' => 'HP131A Toner Cartridge... VAT Inclusive', 'price' => 17200],
//         ['name' => 'Accessories and Supplies Depot Inc.', 'description' => 'HP131A Toner Cartridge... VAT Inclusive', 'price' => 16792],
//         ['name' => 'R4L Enterprises', 'description' => 'HP131A Toner Cartridge... VAT Inclusive', 'price' => 13335.60],
//         ['name' => 'AP Consumer Goods Trading', 'description' => 'HP131A Toner Cartridge... VAT Inclusive', 'price' => 32800.00],
//         ['name' => 'Sunshine Officelink Ventures Corp.', 'description' => 'HP131A Toner Cartridge... VAT Inclusive', 'price' => 21300.00],
//     ]
// ];

// Pagination: 3 suppliers per page. Input Supplier array here
$supplierChunks = array_chunk($data, 3);

// var_dump($supplierChunks);
// die();

$html = '<html><head>
    <style>
        *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        }
        body 
        { 
        font-family: Arial, sans-serif; 
        font-size:12px; 
        padding:32px; 
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        .page-break { page-break-before: always; }
        #pr_qn{
        width:auto;
        padding:0;
        margin:0;
        border: 1px solid white;
        font-size:10px;
}
        #pr_qn *{
        margin:0;
        padding:0 4px;
        border: 1px solid white;
        
}
        #footer {
    margin: 0;
    padding: 0;
    font-size: 10px;
    border: 1px solid white;
}

#footer * {
    margin: 0;
    padding: 0;
    font-size: 10px;
    border: 1px solid white;
}

#footer .row td:nth-child(odd) {
    border-bottom: 1px solid black;
}
#footer .no-border{
border:1px solid white !important;
}
    </style>
</head><body><div class="header">
    <div style="width:100%; text-align:right; font-size:8px;">
        <p>FAD-PPGSS Form No. 1a</p>
        <p>Rev. 0/15-05-17</p>
    </div>
    <div style="text-align:center; font-size:12px;">
        <p>Department of Science and Technology</p>
        <p style="font-size:14px;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</p>
        <p>General Santos Avenue, Bicutan, Taguig City</p>
        <p>ABSTRACT OF CANVASS</p>
        <p>No. ' . $aoc_number . ' dtd. ' . date('m/d/y', strtotime($aoc_date)) . '</p>
    </div>
    
    <table id="pr_qn" >
    <tr>
    <td>PR NO. </td>
    <td>'.$pr_number.'</td>
    <td>dtd</td> 
    <td>'.date('m/d/y', strtotime($prNo_dtd)).'</td>
    </tr>
    <tr>
    <td>QN NO. </td>
    <td>'.$quotation_number.'</td>
    <td>dtd</td> 
    <td>'.date('m/d/y', strtotime($date_quotationno)).'</td>
    </tr>
    </table>';

foreach ($supplierChunks as $pageIndex => $suppliers) {
    if ($pageIndex > 0) {
        $html .= '<div class="page-break"></div>';
    }
    
    $html .= '
    <table>
        <thead>
            <tr>
                <th style="font-weight: normal">Item No.</th>
                <th style="font-weight: normal">Qty.</th>
                <th style="font-weight: normal">Unit</th>
                <th style="font-weight: normal">Particulars</th>';
    foreach ($suppliers as $supplier) {
       
        $html .= '<th>' . $supplier['Supplier'][0]['name'] . '</th>';
    }
    $html .= '</tr>
        </thead>
        <tbody>
            <tr style="vertical-align:top;">
                <td>' . $supplier['Supplier'][0]['itemNo'] . '</td>
                <td>' . $supplier['Supplier'][0]['quantity'] . '</td>
                <td>' . $supplier['Supplier'][0]['unit'] . '</td>
                <td style="text-align: left">' . $supplier['Supplier'][0]['particulars'] . '</td>';
    foreach ($suppliers as $supplier) {
        $html .= '<td style="text-align: right"><strong>PhP' . number_format($supplier['Supplier'][0]['price'], 2) . '</strong><br><br>' . $supplier['Supplier'][0]['description'] . '</td>';
    }
    $html .= '</tr>
        </tbody>
    </table>';
}

$html .= '
<table id="footer">
    <tr>
    <td colspan="3">Based on the above mentioned abstract of canvass, the bid is awarded to:</td>
    <td></td>
    <td> Bids evaluated and awarded:</td>
    <td></td>
    </tr>

    <tr class="row">
    <td width="13%"></td>
    <td style="width:fit-content;">, Item/s No.   </td>
    <td width="12%"></td>
    <td width="34%"></td>
    <td width="14%"></td>
    <td width="20%"></td>
    </tr>

    <tr class="row">
    <td></td>
    <td>, Item/s No.   </td>
    <td></td>
    <td></td>
    <td class="no-border">Enduser</td>
    <td></td>
    </tr>

    <tr class="row">
    <td></td>
    <td>, Item/s No.   </td>
    <td></td>
    <td></td>
    <td class="no-border"></td>
    <td></td>
    </tr>

    <tr class="row">
    <td></td>
    <td>, Item/s No.   </td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    </tr>

    <tr class="row">
    <td></td>
    <td>, Item/s No.   </td>
    <td></td>
    <td></td>
    <td class="no-border">Witness</td>
    <td></td>
    </tr>

    </table>
</body></html>';

$html .= '</body></html>';

// Load HTML content into DOMPDF
$dompdf->loadHtml($html);

// Set paper size to A4 landscape
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Stream the PDF to the browser
$dompdf->stream("abstract_of_canvass.pdf", array("Attachment" => 0));
?>