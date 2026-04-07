<?php
// File: ./graphs_2024/get_yearly_trend_data.php
header('Content-Type: application/json');
require_once(dirname(__DIR__) . '/dbcon.php');

$trend_data = [];

// This powerful SQL query counts all participants and groups them by the year they registered.
$sql = "SELECT 
            YEAR(datetime_registered) as registration_year, 
            COUNT(id) as total_registrants
        FROM 
            participant
        WHERE
            YEAR(datetime_registered) IN (2023, 2024, 2025) -- Define the years you want to track
        GROUP BY 
            registration_year
        ORDER BY 
            registration_year ASC";

$result = $dbcon->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format the data as "year": count
        $trend_data[$row['registration_year']] = $row['total_registrants'];
    }
}

echo json_encode($trend_data);
?>