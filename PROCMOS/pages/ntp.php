<?php

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize DOMPDF with options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Description content
$dateNow = date('F j, Y');
$testDataBidder = [
    'Isagani O. Binaday', // Name
    'General Manager', // Positon
    'Epic Industrial Sales Corp.', //Company
    '#6 Austin St., Dona Manuela I Subd, Pamplona 3, Las Pinas City', //Address Line 1
];
$testDataBid = [
    '2412-1028', // PR No.
    '12/06/2024', // Date
    'One (1)',
    'Lot',
    'EIE Instruments Model GT-101C Water Permeability Tester',
];
$nameArray = explode(' ', $testDataBidder[0]);
$lastName = array_pop($nameArray);
$dated = date('F j, Y', strtotime($testDataBid[1]));
$imagePath = '../photos/director-esig.jpg';
$imageData = base64_encode(file_get_contents($imagePath));
$src = 'data:image/png;base64,'.$imageData;
// Start HTML content
$html = '<html>
<head>
<style>
#noticeToProceed * {
    font-family: Arial;
    font-size: 16px;
    margin: 0;
    padding: 0;
    
}
    #noticeToProceed {
    margin:24px;
    height:95%;
    }
#noticeToProceed span {
    font-weight:bold;
}
</style>
</head>
<body>
<div id="noticeToProceed">
<div >
<p style="font-size:12px;">Stardard Form No. SF-GOOD-56</p>
<p style="font-size:12px;">Revised on: May 24, 2004</p>
</div>
<div style="text-align: center;">
<p style="font-size:14px;">Republic of the Philippines</p>
<p style="font-size:14px;">Philippine Textile Research Institute</p>
<p style="font-size:14px;">Department of Science and Technology</p>
<p style="font-size:14px;">General Santos Avenue, Bicutan, Taguig City</p>
<p style="font-size:14px;">Tel./Fax No. 8371325, 8370744, 8372071 to 80 loc. 2366 & 2373</p>
<p>http://ptri.dost.gov.ph</p>
</div>
<div style="text-align: center; font-weight: bold; margin-top:24px; margin-bottom:24px;">
<p style=" font-size: 18px;">NOTICE TO PROCEED</p>
</div>
<div>
<p>'.$dateNow.'</p>
<br><br>
<p style="font-weight:bold; text-transform:uppercase;">'.$testDataBidder[0].'</p>
<p>'.$testDataBidder[1].'</p>
<p style="font-weight:bold; text-transform:uppercase;">'.$testDataBidder[2].'</p>
<p style="width:30%;">'.$testDataBidder[3].'</p>
<br>
<p style="font-weight:bold;">Dear Mr/Ms. '.$lastName.':</p>
<br>
<p>The attached Contract Agreement, under Purchase Order No. <span>'.$testDataBid[0].'</span> dated <span>'.$dated.'</span> having been approved; notice is hereby given to <span>'.$testDataBidder[2].'</span>, that the work may commence on the procurement of <span>'.$testDataBid[2].' '.$testDataBid[3].' '.$testDataBid[4].'</span>.</p>
<br>
<p>This is to inform you that the said procurement should be delivered and implemented within the period as specified in the Purchase Order.</p>
<br>
<p>Upon receipt of this notice, you are responsible for performing the services under the terms and conditions of the Agreement and in accordance with the Implementation Schedule.</p>
<br>
<p>Please acknowledge receipt and acceptance of this notice by signing both copies in the spaces provided below. Keep one copy and return the other to the <span>PHILIPPINE TEXTILE RESEARCH INSTITUTE</span></p>
<br>
<p>Very truly yours,</p>

<img src='.$src.' alt="Signature" style="width:30%; position:relative; top:20px; left:-12px;"/>
<p style="font-weight:bold;">JULIUS L. LEAÑO, JR.</p>
<p>Director IV</p>
</div>

<div style="position:absolute; bottom:0; width:100%; height:100px;">
<p>I acknowledge receipt of this Notice on ___________________________</p>
<p>Name of the Representative of the Bidder _________________________</p>
<p>Authorized Signature _________________________________________</p>
</div>
</div>
</body>
</html>';

// Load HTML content into DOMPDF
$dompdf->loadHtml($html);

// Set paper size to A4 Portrait
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Stream the PDF to the browser
$dompdf->stream("ntp.pdf", array("Attachment" => 0));
?>
