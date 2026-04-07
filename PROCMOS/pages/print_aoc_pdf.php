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

// Your raw $data from MySQL (example data given)
// $data = [
//     [
//         "acocdetails_ID" => 68,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 1,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Magenta) 008",
//         "supplierName" => "SYSPOINT INC.",
//         "supplierPrice" => "Php 800.00/btl",
//         "supplierDesc" => "DURABrite ET Pigment Magenta Ink<br />Bottle 008"
//     ],
//     [
//         "acocdetails_ID" => 67,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 2,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Cyan) 008",
//         "supplierName" => "SYSPOINT INC.",
//         "supplierPrice" => "Php 800.00/btl",
//         "supplierDesc" => "DURABrite ET Pigment Cyan Ink<br />Bottle 008"
//     ],
//     [
//         "acocdetails_ID" => 66,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 3,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Yellow) 008",
//         "supplierName" => "SYSPOINT INC.",
//         "supplierPrice" => "Php 800.00/btl",
//         "supplierDesc" => "DURABrite ET Pigment Yellow Ink<br />Bottle 008"
//     ],
//     [
//         "acocdetails_ID" => 65,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 45000.00,
//         "itemNo" => 4,
//         "quantity" => 15,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Black) 008",
//         "supplierName" => "SYSPOINT INC.",
//         "supplierPrice" => "Php 750.00/btl",
//         "supplierDesc" => "DURABrite ET Pigment Black Ink<br />Bottle 008"
//     ],
//     [
//         "acocdetails_ID" => 64,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 1,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Magenta) 008",
//         "supplierName" => "COLUMBIA TECHNOLOGIES, INC.",
//         "supplierPrice" => "Php 800.00/btl",
//         "supplierDesc" => "C13T06C300 Epson 008 Magenta Ink Bottle"
//     ],
//     [
//         "acocdetails_ID" => 63,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 2,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Cyan) 008",
//         "supplierName" => "COLUMBIA TECHNOLOGIES, INC.",
//         "supplierPrice" => "Php 790.00/btl",
//         "supplierDesc" => "C13T06C200 Epson 008 Cyan Ink Bottle"
//     ],
//     [
//         "acocdetails_ID" => 62,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 3,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Yellow) 008",
//         "supplierName" => "COLUMBIA TECHNOLOGIES, INC.",
//         "supplierPrice" => "Php 790.00/btl",
//         "supplierDesc" => "C13T06C400 Epson 008 Yellow Ink Bottle"
//     ],
//     [
//         "acocdetails_ID" => 61,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 45000.00,
//         "itemNo" => 4,
//         "quantity" => 15,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Black) 008",
//         "supplierName" => "COLUMBIA TECHNOLOGIES, INC.",
//         "supplierPrice" => "Php 790.00/btl",
//         "supplierDesc" => "C13T06C100 Epson 008 Black Ink Bottle"
//     ],
//     [
//         "acocdetails_ID" => 60,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 1,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Magenta) 008",
//         "supplierName" => "XEFAR ENTERPRISES",
//         "supplierPrice" => "Php 750.00/btl",
//         "supplierDesc" => "Original Epson Ink (Magenta) 008"
//     ],
//     [
//         "acocdetails_ID" => 59,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 2,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Cyan) 008",
//         "supplierName" => "XEFAR ENTERPRISES",
//         "supplierPrice" => "Php 750.00/btl",
//         "supplierDesc" => "Original Epson Ink (Cyan) 008"
//     ],
//     [
//         "acocdetails_ID" => 58,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 3,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Yellow) 008",
//         "supplierName" => "XEFAR ENTERPRISES",
//         "supplierPrice" => "Php 750.00/btl",
//         "supplierDesc" => "Original Epson Ink (Yellow) 008"
//     ],
//     [
//         "acocdetails_ID" => 57,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 45000.00,
//         "itemNo" => 4,
//         "quantity" => 15,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Black) 008",
//         "supplierName" => "XEFAR ENTERPRISES",
//         "supplierPrice" => "Php 750.00/btl",
//         "supplierDesc" => "Original Epson Ink (Black) 008"
//     ],
//     [
//         "acocdetails_ID" => 56,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 1,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Magenta) 008",
//         "supplierName" => "WORDPRINT OFFICE SUPPLIES TRADING",
//         "supplierPrice" => "790.00",
//         "supplierDesc" => "epson 008 - magenta"
//     ],
//     [
//         "acocdetails_ID" => 55,
//         "prNo_dtd" => "2024-04-18 13:56:14",
//         "date_quotationno" => "2024-04-24 11:16:27",
//         "aoc_date" => "2024-07-15 16:54:10",
//         "aoc_number" => "24-296",
//         "pr_number" => "2404-0457",
//         "quotation_number" => "0735-24",
//         "totalcost" => 36000.00,
//         "itemNo" => 2,
//         "quantity" => 10,
//         "unit" => "bottle",
//         "particulars" => "Original Epson Printer Ink (Cyan) 008",
//         "supplierName" => "WORDPRINT OFFICE SUPPLIES TRADING",
//         "supplierPrice" => "790.00",
//         "supplierDesc" => "epson 008 - cyan"
//     ]
// ];

// Step 1: Group data by itemNo, inside group suppliers info
$committee = [
    [
        'name' => 'Evangeline Flor P. Manalang',
        'role' => 'Chairperson',
        'group' => 'Committee',
    ],
    [
        'name' => 'Jenneli E. Caya',
        'role' => 'Vice-Chairperson',
        'group' => 'Committee',
    ],
    
];
$committeeMembers = [
    [
        'name' => 'Merlita R. Odi',
        'role' => 'Member',
        'group' => 'Committee',
    ],
    [
        'name' => 'Marnie B. Dones',
        'role' => 'Member',
        'group' => 'Committee',
    ],
    [
        'name' => 'Anna Margarita I. Argame',
        'role' => 'Member',
        'group' => 'Committee',
    ],
    [
        'name' => 'Ronald S. Pechera',
        'role' => 'Member',
        'group' => 'Committee',
    ],
];
// $twg = [
//     [
//         'name' => 'Mylene S. Dacuya',
//         'role' => 'Lead',
//         'group' => '1',
//     ],
//     [
//         'name' => 'Kent Melvin R. Gomez',
//         'role' => 'Member',
//         'group' => '1',
//     ],
//     [
//         'name' => 'Lyka C. Plazos',
//         'role' => 'Member',
//         'group' => '1',
//     ],
// ];
$enduser = [
    [
        'name' => 'Erlidona G. Verzosa',
        'role' => 'End-user',
        'group' => '1',
    ],
];



$itemsGrouped = [];
$suppliersSet = [];

foreach ($data as $datarow) {
    $itemNo = $datarow['itemNo'];
    $supplierName = $datarow['supplierName'];

    $suppliersSet[$supplierName] = true;  // Collect unique suppliers

    if (!isset($itemsGrouped[$itemNo])) {
        $itemsGrouped[$itemNo] = [
            'itemNo' => $itemNo,
            'quantity' => $datarow['quantity'],
            'unit' => $datarow['unit'],
            'particulars' => $datarow['particulars'],
            'suppliers' => []
        ];
    }
    // Store supplier details inside this itemNo
    $itemsGrouped[$itemNo]['suppliers'][$supplierName] = [
        'price' => $datarow['supplierPrice'],
        'description' => $datarow['supplierDesc']
    ];
}

// Step 2: Extract unique supplier names and chunk into groups of 3
$suppliers = array_keys($suppliersSet);
$supplierChunks = array_chunk($suppliers, 3);

// Define meta variables (replace with your actual values)
// $aoc_number = '12345';
// $aoc_date = '2025-05-01';
// $pr_number = 'PR-2025-0001';
// $prNo_dtd = '2025-04-20';
// $quotation_number = 'QN-2025-0001';
// $date_quotationno = '2025-04-22';

// Setup Dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Begin building HTML
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
</head><body>
<div class="header" style="text-align:center;">
    <p>Department of Science and Technology</p>
    <p style="font-size:14px;">PHILIPPINE TEXTILE RESEARCH INSTITUTE</p>
    <p>General Santos Avenue, Bicutan, Taguig City</p>
    <p>ABSTRACT OF CANVASS</p>
    <p>No. ' . $aoc_number . ' dtd. ' . date('m/d/y', strtotime($aoc_date)) . '</p>
</div>

<table id="pr_qn">
    <tr>
        <td>PR NO.</td>
        <td>' . $pr_number . '</td>
        <td>dtd</td>
        <td>' . date('m/d/y', strtotime($prNo_dtd)) . '</td>
    </tr>
    <tr>
        <td>QN NO.</td>
        <td>' . $quotation_number . '</td>
        <td>dtd</td>
        <td>' . date('m/d/y', strtotime($date_quotationno)) . '</td>
    </tr>
</table>';

// Step 3: Generate tables for each chunk of suppliers
foreach ($supplierChunks as $pageIndex => $chunk) {
    if ($pageIndex > 0) {
        $html .= '<div class="page-break"></div>';
    }

    $html .= '<table><thead><tr style="vertical-align:top;">
        <th>Item No.</th>
        <th>Qty.</th>
        <th>Unit</th>
        <th style="text-align:left;">Particulars</th>';

    // Header: supplier names in this chunk
    foreach ($chunk as $supplierName) {
        $html .= '<th>' . $supplierName . '</th>';
    }
    $html .= '</tr></thead><tbody>';

    // Loop items
    foreach ($itemsGrouped as $item) {
        $html .= '<tr style="vertical-align:top;">
            <td>' . $item['itemNo'] . '</td>
            <td>' . $item['quantity'] . '</td>
            <td>' . $item['unit'] . '</td>
            <td style="text-align:left;">' . $item['particulars'] . '</td>';

        // For each supplier in chunk, display price and description if exists

        foreach ($chunk as $supplierName) {
            if (isset($item['suppliers'][$supplierName])) {
                $supplierData = $item['suppliers'][$supplierName];
                $html .= '<td style="text-align:right;">
                    <strong>PhP ' . number_format($supplierData['price'], 2) . '</strong><br><br>
                    ' . nl2br(($supplierData['description'])) . '
                </td>';
            } else {
                $html .= '<td>-</td>'; // no data for this supplier/item combo
            }
        }

        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
}


if($totalcost > 50000){
    $html .= '
        <style>
        #footer *{
         

         #footer {
            margin: 0;
            padding: 0;
            border: 1px solid white;    
        }

        #footer * {
            margin: 0;
            padding: 0;
            text-align:left;
            vertical-align:top;

        }
        }
        </style>
        <table id="footer">
        <tbody>
          <tr>
            <td rowspan="2" width="45%">

        <p style="margin-bottom:10px;">Based on the above mentioned abstract of canvass, the bid is awarded to:</p>
        ';
        $noOfBids = 3;
        $count = 0;
        while($count <= $noOfBids){
            $html .= '<p style="margin-bottom:5px;">____________________________________, item/s No.   ____________
        </p>';
            $count++;
        }
        $html .= '
        </td>
            <td>

        <p style="font-weight:bold;">Bids and Awards Committee:</p>
        ';
        foreach ($committee as $personnel) {
            $html .= '<div style="margin-top:20px;">
                <p style="font-weight:bold;">' . htmlspecialchars($personnel['name']) . '</p>
                <p>' . htmlspecialchars($personnel['role']) . '</p>
            </div>';
        }
        $html .= '<p style="margin-top:20px;">Members:</p>';
        foreach ($committeeMembers as $member) {
            $html .= '<div style="margin-top:20px;">
                <p style="font-weight:bold;">' . htmlspecialchars($member['name']) . '</p>
            </div>';
        }
        $html.=
        '


        </td>
            <td>

        <p style="font-weight:bold;">Technical Working Group:</p>
        ';
        foreach ($twg as $personnel) {
            $html .= '<div style="margin-top:30px;">
                <p style="font-weight:bold;">' . htmlspecialchars($personnel['twg_Name']) . '</p>
                <p>' . htmlspecialchars($personnel['twg_Position']) . '</p>
            </div>';
        }
        $html.=
        '

        </td>
          </tr>
          <tr>
            <td style="vertical-align:bottom;"><p>End-user: ____________________</p></td>
            <td style="vertical-align:bottom;"><p>Witness: ____________________</p></td>
          </tr>
          </tbody>
        </table>
        ';
}
else{
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

    </table>';
}

$html .= '</body></html>';

// Load HTML to Dompdf
$dompdf->loadHtml($html);

// Setup paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Stream the PDF to browser (0 = preview, 1 = force download)
$dompdf->stream('abstract_of_canvass.pdf', ['Attachment' => 0]);
