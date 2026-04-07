<?php
session_start();
// Check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header("Location: ../Loginn.php");
    exit();
}
 require('../dbcon.php');

$stmt = null;

// Handle form submission to update speaker details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $searchParticipant = $_GET['search'];

    // Ensure search term is not empty before proceeding
    if (!empty($searchParticipant)) {
        // Update speaker details in the database
        $stmt = $dbcon->prepare("SELECT * FROM `participant` WHERE firstName LIKE ? OR username = ? OR id = ? OR lastName LIKE ?");
        $searchTerm = '%' . $searchParticipant . '%';
        $stmt->bind_param("ssss", $searchTerm, $searchParticipant,$searchParticipant,$searchTerm);
    }
}
if(isset($_GET['search'])){
    $searchParticipant=$_GET['search'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Speaker Information</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
   <!-- Bootstrap 5.2 CSS -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

   <!-- Popper.js (required for Bootstrap 5) -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

   <!-- Bootstrap 5.2 JavaScript -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" type="text/css" href="./editParticipants.css">
</head>
<body>
<div class="container pt-5">
    <div>
        <a href="./admindashboard.php" type="button" name="cancel" class="btn btn-danger"><ion-icon name="arrow-back-outline"></ion-icon> Back to Dashboard</a>
    </div>
    <h1 class="text-center">Search Participant</h1>
    <div class="form__container px-5 py-3">
        <form method="GET" action="editParticipants.php" class="d-flex">
            <!-- Input field and search button inside a flex container -->
            <div class="input-group">
                <input type="text" name="search" id="search__participant" class="form-control" placeholder="Search Participant">
                <button type="submit" class="search__button">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>
        </form>
    </div>
    <hr>
    <div class="container d-flex justify-content-start table-responsive">
    <?php
    // Execute query and display results if statement is set
    if ($stmt) {
        if ($stmt->execute()) {
            echo "
            <table class='table table-bordered table-sm text-center align-middle'>
                <tr>
                    <th colspan=3>Name</th>
                    <th rowspan=2>Username</th>
                    <th rowspan=2>Profession</th>
                    <th rowspan=2>Other Profession</th>
                    <th colspan=4>PRC License No.</th>
                    <th rowspan=2>CPD Units</th>
                    <th rowspan=2>Organization</th>
                    <th rowspan=2>Position</th>
                    <th rowspan=2>User Type</th>
                    <th rowspan=2>Date Registered</th>
                    <th rowspan=2>Action</th>
                </tr>
                <tr>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>ChemEng</th>
                    <th>ChemTech</th>
                    <th>Chemist</th>
                    <th>Others</th>
            </tr>
            ";
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Display participant details
                while ($row = $result->fetch_assoc()) {
                    $sql_query_attendance = "SELECT session_number,session_title, session_head,session_date, CASE WHEN session_number IN(SELECT session_id FROM tblattendance WHERE participant_id = ?) THEN 1 ELSE 0 END AS attendance FROM tblptcsession WHERE session_date LIKE '2024%';";
                    $stmt = $dbcon -> prepare($sql_query_attendance);
                    $stmt->bind_param("i", $row['id']);
                    $stmt-> execute();
                    $result1 = $stmt -> get_result();
                    
                    while($row1 = mysqli_fetch_assoc($result1)){
                        // $participant_attendance[$row1['session_head']] = $row1['attendance'];
                        $participant_attendance[$row1['session_number']] = array(
                            'session_head' => $row1['session_head'],
                            'attendance' => $row1['attendance']
                        );
                    }

                    $name = htmlspecialchars($row['firstName']) . " " . htmlspecialchars($row['middleName']) . ". " . htmlspecialchars($row['lastName']);
                    $date = date('m-d-Y', strtotime($row['datetime_registered']));
                    echo "
                    <tr id='values'>
                        <form action='editParticipants.php' method='POST'>
                        <td>
                            <input type='hidden' name=staticID value='".$row['id']."'>
                            <input type='hidden' name=searchTerm value='".$_GET['search']."'>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticFirstName' name='staticFirstName' value='".htmlspecialchars($row['firstName'])."'>
                        </td>
                         <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticMiddleName' name='staticMiddleName' value='".htmlspecialchars($row['middleName'])."'>
                        </td>
                         <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticLastName' name='staticLastName' value='".htmlspecialchars($row['lastName'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticUsername' name='staticUsername' value='".htmlspecialchars($row['username'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticProfession' name='staticProfession' value='".htmlspecialchars($row['profession'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticOtherProfession' name='staticOtherProfession' value='".htmlspecialchars($row['other_profession'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticChemEnPrc' name='staticChemEnPrc' value='".htmlspecialchars($row['ChemEn_prc'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticChemTechPrc' name='staticChemTechPrc' value='".htmlspecialchars($row['ChemTech_prc'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticChemPrc' name='staticChemPrc' value='".htmlspecialchars($row['Chem_prc'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticOthersPrc' name='staticOthersPrc' value='".htmlspecialchars($row['Others_prc'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input'  id='staticCPD' name='staticCPD' value='".htmlspecialchars($row['cpd'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticOrganization' name='staticOrganization' value='".htmlspecialchars($row['organization'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticPosition' name='staticPosition' value='".htmlspecialchars($row['position'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext table-input' id='staticType' name='staticType' value='".htmlspecialchars($row['type'])."'>
                        </td>
                        <td>
                            <input type='text' readonly class='form-control-plaintext w-100 table-input' id='staticDate' name='staticDate' value='".$date."'>
                        </td>
                        <td>
                            <div class='d-flex justify-content-around gap-2'>
                             <span><button type='submit' class='btn btn-primary h-100 submit__button' name='submitEdit'>Submit</button></span>
                            <span><button class='btn btn-danger h-100 cancel__button'>Cancel</button></span>
                            </form>
                                <span><button class='btn btn-warning h-100 edit__button'>Edit</button></span>
                                <span>
                                    <a class='btn btn-danger h-100 attendance__button' href='./editAttendance.php?id=".$row['id']."&search=".$searchParticipant."'>Attendance</a>
                                </span>

                               
                            </div>
                        </td>

                    </tr>

                    ";
                }
            } else {
                echo "<div>No participants found.</div>";
            }
            $stmt->close();
            echo "</table>";
        } else {
            echo "<div>Error executing query.</div>";
        }
    }
    ?>
    <!-- <span><button class='btn btn-danger h-100 attendance__button' onClick='showAttendance(".$row['id'].",".json_encode($participant_attendance).")'>Attendance</button></span> -->
    </div>
</div>
<div class="modal fade" id="modalAttendance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                    
              
                <div>
                    <span>Participant ID:</span>
                    <span id="participant_name"></span>
                </div>
                <div>
                    <div>
                        <table id="list_attendance" class="table table-bordered text-center">
                            <tr>
                                <th>Session</th>
                                <th>Status</th>
                            </tr>
                            <tr >
                                
                            </tr>
                        </table>
                    </div>
                </div>

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
   function showAttendance(participantId, participantAttendance) {
    // Show the modal
    var myModal = document.getElementById('modalAttendance');
    var modal = new bootstrap.Modal(myModal);
    modal.show();

    // Display the participant ID (or you can show the participant's name if you have it)
    document.getElementById('participant_name').textContent = participantId;

    // Assuming participantAttendance is an object (or associative array in PHP)
    var attendanceData = participantAttendance; 

    console.log(attendanceData); // Check the structure of your attendance data

    // Handle the participant attendance table
    var attendanceContainer = document.getElementById('list_attendance');
    
    // Clear any previous attendance data
    attendanceContainer.innerHTML = '';

    // Add table headers
    var headerRow = document.createElement('tr');
    var sessionHeader = document.createElement('th');
    sessionHeader.textContent = 'Session';
    var statusHeader = document.createElement('th');
    statusHeader.textContent = 'Status';
    headerRow.appendChild(sessionHeader);
    headerRow.appendChild(statusHeader);
    attendanceContainer.appendChild(headerRow);

    // Iterate through each session and its attendance status
    for (var session in attendanceData) {
        if (attendanceData.hasOwnProperty(session)) {
            var status = attendanceData[session]['attendance']; // Attendance status (1 or 0)
            var session_head = attendanceData[session]['session_head']; // Session head

            // Create a new table row for each session
            var listRow = document.createElement('tr');

            // Create and append the session name cell
            var sessionCell = document.createElement('td');
            sessionCell.textContent = session_head; // Display session head
            listRow.appendChild(sessionCell);

            // Create the button element for the status
            var statusCell = document.createElement('td');
            var statusButton = document.createElement('button');
            statusButton.textContent = (status === 1 ? 'Present' : 'Absent');
            statusCell.classList.add("d-flex", "justify-content-center");

            // Style the button based on the status
            if (status === 1) {
                statusButton.classList.add("btn", "btn-success", "disabled");
            } else {
                statusButton.classList.add("btn", "btn-danger");
                
            }

            // Add a click event to toggle the attendance status
            statusButton.addEventListener('click', function() {
                if (this.textContent === 'Present') {
                    this.textContent = 'Absent';
                    this.classList.remove("btn-success");
                    this.classList.add("btn-danger");
                    attendanceData[session]['attendance'] = 0; // Mark as Absent
                } else {
                    this.textContent = 'Present';
                    this.classList.remove("btn-danger");
                    this.classList.add("btn-success", "disabled");
                    attendanceData[session]['attendance'] = 1; // Mark as Present
                }

                console.log(attendanceData); // Log the updated attendance data
            });

            statusCell.appendChild(statusButton);
            listRow.appendChild(statusCell);

            // Append the row to the table
            attendanceContainer.appendChild(listRow);
        }
    }
}


</script>
</body>
<script>

   $(document).ready(function() {
       // Initially hide the Submit and Cancel buttons
       $(".submit__button").hide();
       $(".cancel__button").hide();

       // Handle the Edit button click
    $(".edit__button").click(function() {
    var row = $(this).closest("tr");  // Get the row containing the clicked button
    row.find(".edit__button").hide();  // Hide the Edit button
    row.find(".attendance__button").hide();  // Hide the Attendance button
    row.find(".submit__button").show();  // Show the Submit button
    row.find(".cancel__button").show();  // Show the Cancel button

    // Remove readonly from input fields (in all table data cells within the row)
    row.find("input").not(":last").prop("readonly", false);

    // Optionally, add a class to highlight editable fields if needed
    row.find("input").not(":last").removeClass("form-control-plaintext");
    row.find("input").not(":last").addClass("form-control");
});


       $(".cancel__button").click(function() {
           var row = $(this).closest("tr");  // Get the row containing the clicked button
           row.find(".edit__button").show();  // Hide the Edit button
           row.find(".attendance__button").show();  // Hide the Attendance button
           row.find(".submit__button").hide();  // Show the Submit button
           row.find(".cancel__button").hide();  // Show the Cancel button
           row.find("input").prop("readonly", true);
            row.find("input").addClass("form-control-plaintext");
            row.find("input").removeClass("form-control");
       });


   });

   const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      iconColor: 'white',
      customClass: {
        popup: 'colored-toast',
      },
      showConfirmButton: false,
      timer: 1500,
      timerProgressBar: true,
    })
    </script>

</html>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submitEdit'])){

    $firstName = $_POST['staticFirstName'] ?? null;
    $middleName = $_POST['staticMiddleName'] ?? null;
    $lastName = $_POST['staticLastName'] ?? null;
    $username = $_POST['staticUsername'] ?? null;
    $profession = $_POST['staticProfession'] ?? null;
    $other_profession = $_POST['staticOtherProfession'] ?? null;
    $chem_en_prc = $_POST['staticChemEnPrc'] ?? null;
    $chem_tech_prc = $_POST['staticChemTechPrc'] ?? null;
    $chem_prc = $_POST['staticChemPrc'] ?? null;
    $others_prc = $_POST['staticOthersPrc'] ?? null;
    $cpd = $_POST['staticCPD'] ?? null;
    $organization = $_POST['staticOrganization'] ?? null;
    $position = $_POST['staticPosition'] ?? null;
    $user_type = $_POST['staticType'] ?? null;

        // Update speaker details in the database
        $stmt_edit = $dbcon->prepare("UPDATE participant 
            SET 
                firstName = ?,
                middleName = ?,
                lastName = ?,
                username = ?,
                profession = ?,
                other_profession = ?,
                ChemEn_prc = ?,
                ChemTech_prc = ?,
                Chem_prc = ?,
                Others_prc = ?,
                cpd = ?,
                organization = ?,
                position = ?,
                type=?
            WHERE
                id = ?
            ");
        $stmt_edit->bind_param("ssssssssssisssi", $firstName, $middleName, $lastName, $username, $profession, $other_profession, $chem_en_prc, $chem_tech_prc, $chem_prc, $others_prc, $cpd, $organization, $position, $user_type,$_POST['staticID']);
        if($stmt_edit){
            if($stmt_edit->execute()){
                echo "<script>
                Toast.fire({
                     icon: 'success',
                     title: 'Success',

                }).then(() => {
                    window.location.href = 'editParticipants.php?search=" . $_POST['searchTerm']. "';
                });
                </script>";
            }
        }
}
// Close the database connection
$dbcon->close();
?>
