<?php
// config.php

// This is the single, central base URL for your entire application.

$host = $_SERVER["SERVER_NAME"];

if ($host === "localhost" || $host === "127.0.0.1" || $host === "10.10.11.33") {
    define('BASE_URL', 'http://10.10.11.33:8081');
    define('BASE_PATH', '');
} else {
    define('BASE_URL', 'https://philippinetextilecongress.com');
    define('BASE_PATH', '');
}
?>
