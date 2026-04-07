<?php
require_once('../dbcon.php');

if(isset($_POST['ctid'])){
    $provinceCode = $_POST['ctid'];

    // This query is now secure using a prepared statement
    $stmt = $dbcon->prepare("SELECT municipalCode, municipalDesc FROM tbl_municipalities WHERE provCode = ? ORDER BY municipalDesc ASC");
    $stmt->bind_param("s", $provinceCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<option value='' disabled selected>Select City</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['municipalCode']) . "'>" . htmlspecialchars($row['municipalDesc']) . "</option>";
        }
    } else {
        echo "<option value=''>No cities found for this province</option>";
    }
    $stmt->close();
}
?>