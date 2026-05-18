<?php
// config.php

// This is the single, central base URL for your entire application.

$host = $_SERVER["SERVER_NAME"];

if ($host === "localhost") {
    define('BASE_URL', 'https://localhost/ptc');
    define('BASE_PATH', '/ptc');
} else {
    define('BASE_URL', 'https://philippinetextilecongress.com');
    define('BASE_PATH', '');
}
?>