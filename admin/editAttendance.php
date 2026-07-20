<?php
session_start();
// Check if admin is logged in
if (empty($_SESSION['admin_id'])) {
    header("Location: ../Loginn.php");
    exit();
}
 require('../dbcon.php');
 $userId = isset($_GET['id'])? $_GET['id']:'';
 $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
 $availableYears = [];
 $yearResult = $dbcon->query("SELECT DISTINCT YEAR(session_date) AS session_year FROM tblptcsession WHERE session_date IS NOT NULL ORDER BY session_year DESC");
 if ($yearResult) {
     while ($yearRow = $yearResult->fetch_assoc()) {
         if (!empty($yearRow['session_year'])) {
             $availableYears[] = (int) $yearRow['session_year'];
         }
     }
 }
 $selectedYear = isset($_GET['year']) ? (int) $_GET['year'] : ($availableYears[0] ?? (int) date('Y'));
 if (!in_array($selectedYear, $availableYears, true) && !empty($availableYears)) {
     $selectedYear = $availableYears[0];
 }
 $sql_query_participant = "SELECT sampledb.participant.*, world.countries.name AS 'countryName'  FROM sampledb.participant INNER JOIN world.countries ON world.countries.iso2 = sampledb.participant.Country WHERE sampledb.participant.id = ?";
 $stmt = $dbcon -> prepare($sql_query_participant);
 $stmt -> bind_param("i",$userId);
 $stmt->execute();
 $totalQualified=0;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Participant Information</title>
    <script type="text/javascript" src="js/jquery.min.js"></script>
   <!-- Bootstrap 5.2 CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Popper.js (required for Bootstrap 5) -->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

   <!-- Bootstrap 5.2 JavaScript -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" type="text/css" href="./editParticipants.css">
</head>
<body>

<div class="container" id="modalAttendance">
    <div >
        <div >
            <div class="pt-5 mt-5">
                <a href="<?php echo "./editParticipants.php?search=".$searchTerm; ?>" type="button" name="cancel" class="btn btn-danger"><ion-icon name="arrow-back-outline"></ion-icon> Back to Search</a>
            </div>
            <div class="d-flex justify-content-center">
                <h3 class="fw-bold" id="exampleModalLabel">Attendance and Feedback</h3>
            </div>
            <form method="GET" action="editAttendance.php" class="d-flex justify-content-center align-items-end gap-2 mb-3">
                <input type="hidden" name="id" value="<?= htmlspecialchars($userId) ?>">
                <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                <div>
                    <label for="year" class="form-label fw-bold mb-1">Congress year</label>
                    <select id="year" name="year" class="form-select" onchange="this.form.submit()">
                        <?php foreach ($availableYears as $year): ?>
                            <option value="<?= $year ?>" <?= $year === $selectedYear ? 'selected' : '' ?>><?= $year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <noscript><button type="submit" class="btn btn-primary">Apply</button></noscript>
            </form>
            <div class="modal-body">
                
            <?php 
                        $result = $stmt->get_result();
                        if($row = mysqli_fetch_assoc($result)){
                           
                            $firstName = $row['firstName'];
                            $middleName = $row['middleName'];
                            $lastName = $row['lastName'];
                            $Gender = $row['Gender'];
                            $countryName = $row['countryName'];
                            $organization = $row['organization'];
                            $position = $row['position'];
                            // process profession data
                            $profession = explode(",",$row['profession']);
                            if(in_array("Others",$profession)){
                                array_push($profession,$row['other_profession']);
                            }
                            $newProfession=[];
                            foreach($profession as $item){
                                if($item == 'Others'){
                                    continue;
                                }
                                array_push($newProfession,$item);
                            }
                            $professionString = implode(", ", $newProfession);  // Join the remaining array into a string
                            
                        }
                    ?>
              
                <div>
                    <span>Participant ID:</span>
                    <span id="participant_name" class="fw-bold"><?php echo $userId; ?></span>
                    
                </div>
                <div>
                    <span>Profession:</span>
                    <span id="participant_profession" class="fw-bold"><?php echo $professionString; ?></span>
                </div>
                <div>
                        <?php
                        if(trim($row['ChemEn_prc']) && isset($row['ChemEn_prc'])){
                            echo '<div><span>Chemical Engineer PRC:</span>
                    <span class="fw-bold">'.$row['ChemEn_prc'].'</span></div>';
                        }
                        if(trim($row['ChemTech_prc']) && isset($row['ChemTech_prc'])){
                            echo '<div><span>Chemist PRC:</span>
                    <span class="fw-bold">'.$row['ChemTech_prc'].'</span></div>';
                        }
                        if(trim($row['Chem_prc']) && isset($row['Chem_prc'])){
                            echo '<div><span>Chemist PRC:</span>
                    <span class="fw-bold">'.$row['Chem_prc'].'</span></div>';
                        }
                        if(trim($row['Others_prc']) && isset($row['Others_prc'])){
                            echo '<div><span>Chemist PRC:</span>
                    <span class="fw-bold">'.$row['Others_prc'].'</span></div>';
                        }
                        ?>
                </div>
                <div>
                    <h5>
                        
                        <span class="fw-bold"></span>
                    <span>Name:</span>
                    <span class="fw-bold"><?php  echo $firstName." ".$middleName.". ".$lastName?></span>
                    </h5>
                    
                </div>

                <div>
                    <div>
                        <table id="list_attendance" class="table table-bordered text-center">
                            <tr>
                                <th>Session</th>
                                <th>Qualified</th>
                                <th>Attendance</th>
                                <th>Feedback</th>
                            </tr>
                            <?php
                                    $sql_query_attendance = "SELECT 
                                        session_number,
                                        session_title, 
                                        session_head,
                                        session_date, 
                                        CASE WHEN session_number IN(SELECT session_id FROM tblattendance WHERE participant_id = ?) THEN 1 ELSE 0 END AS attendance,
                                        CASE WHEN session_number IN(SELECT session_id FROM tblfeedback WHERE participant_id = ?) THEN 1 ELSE 0 END AS feedback,
                                        (CASE WHEN session_number IN(SELECT session_id FROM tblattendance WHERE participant_id = ?) THEN 1 ELSE 0 END) *
                                        (CASE WHEN session_number IN(SELECT session_id FROM tblfeedback WHERE participant_id = ?) THEN 1 ELSE 0 END) AS qualified
                                    FROM tblptcsession 
                                    WHERE YEAR(session_date) = ?
                                    ORDER BY session_number ASC;";
                                    $stmt = $dbcon -> prepare($sql_query_attendance);
                                    $stmt->bind_param("iiiii", $userId, $userId, $userId, $userId, $selectedYear);
                                    $stmt-> execute();
                                    $result1 = $stmt -> get_result();
                                    while($row1=mysqli_fetch_assoc($result1)){                                        
                                 ?>
                            <tr >
                                
                                 <td><?php echo $row1['session_head'].": ".$row1['session_title'];?></td>
                                 <td>
                                    <?php
                                     
                                    if($row1['qualified']){
                                        echo '<p class="text-success fw-bold">Yes</p>';
                                        $totalQualified++;
                                    }else{
                                        echo '<p class="text-danger fw-bold">No</p>';
                                    }
                                    ?>
                                 </td>
                                 <form action="editAttendance.php?id=<?= urlencode($userId) ?>&search=<?= urlencode($searchTerm) ?>&year=<?= $selectedYear ?>" method="POST">
                                        <input type="hidden" name="searchTerm" value="<?php echo $searchTerm;?>">
                                     <td><button class="btn <?php echo $row1['attendance']?'btn-success disabled':'btn-danger';?>" name="attendance<?php echo $row1['session_number']?>"><?php echo $row1['attendance']?'Present':'Absent';?></button></td>
                                 </form>
                                 <td>
                                 <?php if($row1['feedback']){
                                        echo '<p class="text-success fw-bold">Yes</p>';
                                    }else{
                                        echo '<p class="text-danger fw-bold">No</p>';
                                    }
                                    ?>
                                 </td>
                            </tr>
                             <?php 
                             if(isset($_POST["attendance".$row1['session_number']])){
                                $stmt1 = $dbcon->prepare("INSERT INTO `tblattendance` (`session_id`, `session_head`, `participant_id`, `fname`, `mName`, `lName`, `Gender`, `Country`, `Institution`, `Position`) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                                // Bind parameters to the prepared statement
                                $stmt1->bind_param("isisssssss", 
                                    $row1['session_number'], 
                                    $row1['session_head'], 
                                    $userId, 
                                    $firstName, 
                                    $middleName, 
                                    $lastName, 
                                    $Gender, 
                                    $countryName, 
                                    $organization, 
                                    $position
                                );

                                // Execute the statement
                                if($stmt1->execute()){
                                    echo "<script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Attendance has been marked!',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(function() {
                                            // Correcting the window.location redirect
                                            window.location.href = './editParticipants.php?search=" . urlencode($_GET['search']) . "';
                                        });
                                    </script>";
                                }



                             }

                         }?>
                        </table>
                        <div class="position-absolute top-0" style="right:0;">
                            <div class="mb-2">
                            <span class="h3">TOTAL QUALIFIED: </span>
                            <span class=" h3 fw-bold 
                            <?php
                            if($totalQualified>7){
                                echo 'text-success';
                            }else{
                                echo 'text-danger';
                            }
                            ?>
                            "><?php echo $totalQualified;?></span>
                            </div>
                            <div class="d-grid">
                                <button onclick="window.open('./certificate/generate_certificate.php?id=<?= urlencode($userId) ?>&year=<?= $selectedYear ?>&source=admin')" class="btn btn-info text-white">
                                    View <?= $selectedYear ?> certificate
                                </button>
                                </div>

                        </div>
                    </div>
                </div>

              
            </div>
        </div>
    </div>
</div>

</body>

</html>

<?php
// Close the database connection
$dbcon->close();
?>
