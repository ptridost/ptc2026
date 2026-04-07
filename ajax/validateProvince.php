<?php
// This script now uses your main database connection file
require_once('../dbcon.php'); 

// This query is now secure and correctly fetches provinces from your 'sampledb'
$sql = "SELECT provCode, provDesc FROM tbl_provinces ORDER BY provDesc ASC";
$result = $dbcon->query($sql);

if ($result && $result->num_rows > 0) {
    echo '<option value="" disabled selected>Select Province</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . htmlspecialchars($row['provCode']) . '">' . htmlspecialchars($row['provDesc']) . '</option>';
    }
} else {
    echo '<option value="">No provinces found</option>';
}
?>