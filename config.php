<?php
// config.php

// This is the single, central base URL for your entire application.

$host = $_SERVER["SERVER_NAME"];

if ($host === "localhost" || $host === "127.0.0.1" || $host === "10.10.11.16") {
    define('BASE_URL', 'http://10.10.11.16:8000/');
    define('BASE_PATH', '/');
} else {
    define('BASE_URL', 'https://philippinetextilecongress.com');
    define('BASE_PATH', '');
}
?>
