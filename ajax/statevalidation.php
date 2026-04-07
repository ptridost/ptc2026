<?php
// This script now uses your main world database connection
require_once('../dbcon_world.php');

if(isset($_POST['isoID'])){
    $countryCode = $_POST['isoID'];

    // This query is now secure using a prepared statement
    $stmt = $dbcon_world->prepare("SELECT id, name FROM states WHERE country_code = ? ORDER BY name ASC");
    $stmt->bind_param("s", $countryCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<option value='' disabled selected>Select Region/State</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
        }
    } else {
        echo "<option value=''>No states found for this country</option>";
    }
    $stmt->close();
}
?>