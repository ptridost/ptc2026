<?php
require_once('../dbcon_world.php');

if(isset($_POST['citycode'])){
    $stateId = $_POST['citycode'];

    // This query is now secure using a prepared statement
    $stmt = $dbcon_world->prepare("SELECT name FROM cities WHERE state_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $stateId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo "<option value='' disabled selected>Select City/Province</option>";
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
        }
    } else {
        echo "<option value=''>No cities found for this state</option>";
    }
    $stmt->close();
}
?>