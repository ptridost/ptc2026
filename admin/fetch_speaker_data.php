<?php
header('Content-Type: application/json');
require('../dbcon.php');

function getImagePath($filename) {
    if (empty($filename)) {
        return 'placeholder.jpg';
    }

    // --- Paths for the PHP file_exists() check (Absolute Server Paths) ---
    // __DIR__ is the directory of the current file: /admin
    $absolute_new_path = __DIR__ . '/speaker_images/' . $filename;
    $absolute_old_path = __DIR__ . '/../User_Dashboard/2024/' . $filename;

    // --- Paths to be returned for the HTML <img> tag (Relative Web URLs) ---
    $relative_new_path_for_browser = './speaker_images/' . $filename;
    $relative_old_path_for_browser = '../User_Dashboard/2024/' . $filename;

    // Now, we check the reliable absolute paths, but return the correct relative paths for the browser.
    if (file_exists($absolute_new_path)) {
        return $relative_new_path_for_browser;
    } elseif (file_exists($absolute_old_path)) {
        return $relative_old_path_for_browser;
    }

    return 'placeholder.jpg';
}
// Check if speaker_id is provided
if (isset($_GET['speaker_id']) && is_numeric($_GET['speaker_id'])) {
    $speaker_id = $_GET['speaker_id'];

    $stmt = $dbcon->prepare("SELECT * FROM tblspeakerses WHERE speaker_id = ?");
    $stmt->bind_param("i", $speaker_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $row['full_image_path'] = getImagePath($row['speaker_Img']);
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Speaker not found."]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid speaker ID."]);
}

$dbcon->close();
?>