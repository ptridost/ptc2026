<?php
require 'C:/xampp/htdocs/EvAMS/vendor/autoload.php';

$imagick = new Imagick();
$version = Imagick::getVersion();
echo "Version: " . $version['versionString'] . "<br>";

// This shows where ImageMagick looks for config files
putenv('MAGICK_DEBUG=exception');
echo shell_exec('magick -list configure') . "<br>";

// Check registry
echo "MAGICK_HOME: " . getenv('MAGICK_HOME') . "<br>";
echo "MAGICK_CONFIGURE_PATH: " . getenv('MAGICK_CONFIGURE_PATH') . "<br>";


echo "GS Test: ";
echo shell_exec('"C:\\Program Files\\gs\\gs10.07.1\\bin\\gswin64c.exe" --version');
echo "<br>";

echo "Imagick loaded: " . (extension_loaded('imagick') ? 'YES' : 'NO') . "<br>";

$pdfPath = 'C:\\xampp\\htdocs\\EvAMS\\storage\\app\\public\\event\\programs\\onward_philippine_nonwoven_textile_innovations\\onward_philippine_nonwoven_textile_innovations_program.pdf';

echo "PDF exists: " . (file_exists($pdfPath) ? 'YES' : 'NO') . "<br>";

echo "Spatie test: ";
try {
    $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
    echo "OK - Pages: " . $pdf->getNumberOfPages();
} catch (\Throwable $e) {
    echo "<br>TYPE: " . get_class($e);
    echo "<br>MESSAGE: " . $e->getMessage();
    echo "<br>FILE: " . $e->getFile() . " line " . $e->getLine();
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

require 'C:/xampp/htdocs/evams/vendor/autoload.php';

// Check what ImageMagick version PHP's imagick extension is linked to
$imagick = new Imagick();
echo "Imagick version: " . Imagick::getVersion()['versionString'] . "<br>";

// Check where it looks for delegates
echo "ImageMagick home: " . ini_get('imagick.locale_fix') . "<br>";

// Check all ImageMagick configure options
$info = $imagick->getConfigureOptions();
echo "<pre>";
print_r($info);
echo "</pre>";

$imagick = new Imagick();
echo shell_exec('where magick');
echo Imagick::getHomeURL();


$imagick = new Imagick();
$registry = $imagick->getRegistry('MAGICK_CONFIGURE_PATH');
echo "Configure path: " . $registry . "<br>";

// Also try
echo shell_exec('where gs') . "<br>";

// Check XAMPP's ImageMagick folder
echo file_exists('C:\\xampp\\php\\extras\\imagick\\delegates.xml') ? 'Found in xampp extras' : 'Not there';
echo "<br>";
echo file_exists('C:\\xampp\\ImageMagick\\delegates.xml') ? 'Found in xampp ImageMagick' : 'Not there';

// Search for delegates.xml on the whole system
$locations = [
    'C:\\xampp\\php\\delegates.xml',
    'C:\\xampp\\php\\ext\\delegates.xml',
    'C:\\Windows\\System32\\delegates.xml',
    'C:\\Program Files\\ImageMagick-7.1.0-18\\delegates.xml',
    'C:\\Program Files\\ImageMagick-7.1.1-Q16-HDRI\\delegates.xml',
    'C:\\Program Files\\ImageMagick-7.1.2-Q16-HDRI\\delegates.xml',
    'C:\\ProgramData\\ImageMagick\\delegates.xml',
];

foreach ($locations as $path) {
    echo $path . ': ' . (file_exists($path) ? '<b>FOUND</b>' : 'not found') . '<br>';
}

echo "PHP Version: " . phpversion() . "<br>";
echo "Thread Safe: " . (ZEND_THREAD_SAFE ? 'YES (TS)' : 'NO (NTS)') . "<br>";
echo "Architecture: " . (PHP_INT_SIZE === 8 ? 'x64' : 'x86') . "<br>";
phpinfo();