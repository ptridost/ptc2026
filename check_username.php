<?php
// Use your standard, secure database connection
require('dbcon.php');

// 1. Check if the username was sent via POST
if(isset($_POST['username'])){

    // 2. Get the username from the form data
    $username = $_POST['username'];

    // 3. Prepare a secure SQL statement
    // This query is much faster as it only checks for the existence of 1 row
    $stmt = $dbcon->prepare("SELECT username FROM participant WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // 4. Check if any rows were found
    if($stmt->num_rows > 0){
        // If a row was found, the username is taken
        echo "<span style='color: red;'>Username is already registered.</span>";
    } else {
        // If no rows were found, the username is available
        echo "<span style='color: green;'>Username is Available.</span>";
    }

    // 5. Always close the statement
    $stmt->close();
}
?>