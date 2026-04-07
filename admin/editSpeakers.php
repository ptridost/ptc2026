<?php
date_default_timezone_set('Asia/Manila');
session_start();

if (empty($_SESSION['admin_id'])) {
    header("Location: ../Loginn.php");
    exit();
}

require('../dbcon.php');

$session_id_for_page = $_GET['id'] ?? null;

// --- HANDLE FORM SUBMISSION AT THE TOP ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_speaker_submit'])) {
    $speaker_id = $_POST['speaker_id'];
    $speakerName = $_POST['speakerName'];
    $speakerInfo = $_POST['speakerInfo'];
    $speakerGender = $_POST['speakerGender'];
    $current_image_filename = $_POST['currentSpeakerImg'];
    $manually_typed_filename = $_POST['speakerImg'];
    $session_id_redirect = $_POST['session_id_hidden'];
    
    $message = null;
    $image_to_save = $current_image_filename;

    if (isset($_FILES['newSpeakerImage']) && $_FILES['newSpeakerImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['newSpeakerImage']['tmp_name'];
        $fileName = $_FILES['newSpeakerImage']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedfileExtensions = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // FIX #1: Using the correct, centralized upload folder
            $uploadFileDir = './speaker_images/';
            if (!is_dir($uploadFileDir)) { mkdir($uploadFileDir, 0755, true); }
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image_to_save = $newFileName;
                if ($current_image_filename && file_exists($uploadFileDir . $current_image_filename)) {
                    unlink($uploadFileDir . $current_image_filename);
                }
            } else { $message = "Error: Could not move uploaded file."; }
        } else { $message = "Error: Invalid file type."; }
    } else {
        $image_to_save = $manually_typed_filename;
    }

    if (empty($message)) {
        $stmt = $dbcon->prepare("UPDATE tblspeakerses SET speaker_Name = ?, speaker_Info = ?, speaker_Img = ?, spkr_gender = ? WHERE speaker_id = ?");
        $stmt->bind_param("ssssi", $speakerName, $speakerInfo, $image_to_save, $speakerGender, $speaker_id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Speaker details updated successfully!'];
        } else { 
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Error updating speaker in the database.'];
        }
        $stmt->close();
    } else {
        $_SESSION['flash_message'] = ['type' => 'error', 'text' => $message];
    }
    header("Location: editSpeakers.php?id=" . urlencode($session_id_redirect));
    exit();
}

// --- FETCH DATA FOR PAGE DISPLAY ---
$speakers = [];
if ($session_id_for_page && is_numeric($session_id_for_page)) {
    $stmt = $dbcon->prepare("SELECT speaker_id, speaker_Name FROM tblspeakerses WHERE session_id = ?");
    $stmt->bind_param("i", $session_id_for_page);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $speakers[] = $row; }
    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Speaker Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./editSpeakers.css?v=1.1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card edit-speaker-card">
                <div class="card-header text-center">
                    <h2>Edit Speaker Information</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if (empty($session_id_for_page) || !is_numeric($session_id_for_page)): ?>
                        <div class="alert alert-danger">Invalid or missing Session ID. Please <a href="admindashboard.php">return to the dashboard</a>.</div>
                    <?php else: ?>
                        <form method="POST" action="editSpeakers.php" enctype="multipart/form-data">
                            <input type="hidden" name="speaker_id" id="speaker_id">
                            <input type="hidden" name="session_id_hidden" value="<?php echo htmlspecialchars($session_id_for_page); ?>">
                            <input type="hidden" name="currentSpeakerImg" id="currentSpeakerImg">

                            <div class="mb-4">
                                <label for="speaker_list" class="form-label fs-5">Select Speaker to Edit</label>
                                <select id="speaker_list" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>-- Choose a Speaker --</option>
                                    <?php foreach ($speakers as $speaker): ?>
                                        <option value="<?php echo $speaker['speaker_id']; ?>"><?php echo htmlspecialchars($speaker['speaker_Name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="mb-3"><label for="speakerName" class="form-label">Speaker Name</label><input type="text" class="form-control" id="speakerName" name="speakerName"></div>
                                    <div class="mb-3"><label for="speakerInfo" class="form-label">Speaker Description</label><textarea class="form-control" id="speakerInfo" name="speakerInfo" rows="4"></textarea></div>
                                    <div class="mb-3"><label for="speakerGender" class="form-label">Speaker Gender</label><input type="text" class="form-control" id="speakerGender" name="speakerGender"></div>
                                </div>
                                <div class="col-md-5">
                                    <div class="text-center mb-3"><label class="form-label fw-bold">Image Preview</label><div class="image-preview-container"><img id="speakerImgUrl" src="placeholder.jpg" alt="Speaker Image"></div></div>
                                    <div class="mb-3"><label for="speakerImg" class="form-label">Current Image Filename</label><input type="text" class="form-control" id="speakerImg" name="speakerImg"></div>
                                    <div class="mb-3"><label for="newSpeakerImage" class="form-label">Or Upload New Image</label><input class="form-control" type="file" id="newSpeakerImage" name="newSpeakerImage"></div>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="text-end">
                                <a href="./admindashboard.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="update_speaker_submit" class="btn btn-primary">Update Speaker</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
        $("#speaker_list").change(function() {
        var speaker_id = $(this).val();
        if (speaker_id) {
            $.ajax({
                url: "fetch_speaker_data.php",
                method: "GET",
                data: { speaker_id: speaker_id },
                dataType: "json",
                success: function(data) {
                    if (data && !data.error) {
                        $("#speakerName").val(data.speaker_Name);
                        $("#speakerInfo").val(data.speaker_Info);
                        $("#speakerImg").val(data.speaker_Img);
                        $("#speakerGender").val(data.spkr_gender);
                        $("#speaker_id").val(data.speaker_id);
                        $("#currentSpeakerImg").val(data.speaker_Img);
                        $("#speakerImgUrl").attr('src', data.full_image_path);
                    } else {
                        // This will run if the PHP script returns an error message
                        alert("Error from server: " + data.error);
                    }
                },
                // THIS IS THE NEW PART: It catches network or server errors
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX call failed!");
                    console.error("Status: " + textStatus);
                    console.error("Error thrown: " + errorThrown);
                    console.error("Response Text:", jqXHR.responseText);
                    alert("A network error occurred. Please check the console (F12) for more details.");
                }
            });
        }
    });
    $("#newSpeakerImage").change(function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#speakerImgUrl').attr('src', e.target.result);
                $('#speakerImg').val( $('#newSpeakerImage').val().split('\\').pop() );
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>

<?php
// FIX #3: Added the flash message display script
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    echo "<script>
        Swal.fire({
            icon: '" . $message['type'] . "',
            title: '" . ucfirst($message['type']) . "!',
            text: '" . addslashes($message['text']) . "'
        });
    </script>";
    unset($_SESSION['flash_message']);
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$dbcon->close();
?>