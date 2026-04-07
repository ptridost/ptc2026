<?php
  session_start();

  // var_dump("test");
  // die();
  if(!empty($_SESSION['admin_id'])){
    // header("Location: admin/admindashboard.php");
  }
  else{
    header("Location: ../Loginn.php"); 
  }
require('../dbcon.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="feedbackform.css">
    <title>My performance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="stylesheet" type="text/css" href="user_head&footer/footer.css">
  <link rel="stylesheet" type="text/css" href="rating.css">
   <link rel="stylesheet" type="text/css" href="user_head&footer/user_nav.css">
   <link rel="stylesheet" type="text/css" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    
  <div class="container atten_con">
    <div class="row">
      
      <div class="col-md-12 atten_title">
       
        <h1>OVERALL SUMMARY REPORT SHEET</h1>
    </div></div>
    <br>
    <br>

<?php
// All sessions
$sql = "SELECT * FROM tblptcsession";
$all_session = $dbcon->query($sql);

$sql_country = "SELECT Country
FROM tblattendance 
GROUP BY Country 
ORDER BY 
    CASE 
        WHEN Country = 'Philippines' THEN 1
        ELSE 2
    END, 
    Country;
;";
$country_count = $dbcon -> query($sql_country);

        
?>

<table style="border: 2px solid black; border-collapse: collapse;
  width: 100%;">
  <tr  style="border: 2px solid black;">
    <th style="border: 2px solid black; text-align: center;" rowspan="2"><p style="font-size: 25px; margin-bottom: -20px; color: #2ba3d9;">SESSIONS</p><br><p style="font-size: 15px; margin-bottom: -10px; color: #3cbcf6; font-family: arial normal"><i></i></p></th>
    <th style=" border: 2px solid black;text-align: center;" rowspan="2">TOTAL ATTENDEES</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="3">GENDER</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="5">PROFESSION</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="7">AGE</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="<?php echo mysqli_num_rows($country_count);?>">LOCATION</th>
    <th style=" border: 2px solid black;text-align: center;" rowspan="2">SESSION TOTAL SPEAKERS</th>
    <th style=" border: 2px solid black;text-align: center;" colspan="2">GENDER</th>
  </tr>
   <tr style="border: 2px solid black;">
    <td style=" border: 2px solid black;text-align: center;">MALE</td>
    <td style=" border: 2px solid black;text-align: center;">FEMALE</td>
    <td style=" border: 2px solid black;text-align: center;">NOT SPECIFY</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMICAL ENGINEER</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMICAL TECHNICIAN</td>
    <td style=" border: 2px solid black;text-align: center;">CHEMIST</td>
    <td style=" border: 2px solid black;text-align: center;">OTHERS</td>
    <td style=" border: 2px solid black;text-align: center;">NONE</td>
    <td style=" border: 2px solid black;text-align: center;">61 AND ABOVE</td>
    <td style=" border: 2px solid black;text-align: center;">51-60</td>
    <td style=" border: 2px solid black;text-align: center;">41-50</td>
    <td style=" border: 2px solid black;text-align: center;">31-40</td>
    <td style=" border: 2px solid black;text-align: center;">21-30</td>
    <td style=" border: 2px solid black;text-align: center;">LESS THAN 21</td>
    <td style=" border: 2px solid black;text-align: center;">NONE</td>
    <?php
    while($row = mysqli_fetch_assoc($country_count)){
        echo "<th style='border: 2px solid black;text-align: center;'>".$row['Country']."</th>";
    }
    ?>
    
    <td style=" border: 2px solid black;text-align: center;">MALE</td>
    <td style=" border: 2px solid black;text-align: center;">FEMALE</td>
  </tr>
  <?php
  while($row = mysqli_fetch_assoc($all_session)){
    $sesid = $row['session_number'];
    ?>
  <tr style="border: 2px solid black;">
    <td style=" border: 2px solid black;text-align: center;"><?php echo $row['session_head']; ?></td>
<?php
$sql0 = "SELECT COUNT(*) AS 'total' FROM tblattendance WHERE session_id = ".$sesid;
$all_session0 = $dbcon->query($sql0);

while($row = mysqli_fetch_assoc($all_session0)){
        
        $count2 = $row['total'];
?>
    <td style=" border: 2px solid black;text-align: center;"><?php echo $count2; ?></td>
<?php
}
$sql1 = "SELECT Gender, COUNT(Gender) AS 'total' FROM tblattendance WHERE session_id =".$sesid." GROUP BY Gender ORDER BY CASE WHEN Gender = 'Male' THEN 1 WHEN Gender = 'Female' THEN 2 WHEN Gender = 'Not Specify' THEN 3 ELSE 4 END;";
$all_session1 = $dbcon->query($sql1);

while($row = mysqli_fetch_assoc($all_session1)){

  echo "<td style='border: 2px solid black;text-align: center;'>".$row['total']."</td>";
}
$sql4 = "SELECT 'Chemical Engineer' AS profession, 
       COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Engineer', profession) OR LOWER(other_profession) LIKE '%chemical engineer%' THEN participant_id END) AS profession_count
FROM sampledb.participant 
INNER JOIN tblattendance ON participant_id = participant.id 
WHERE session_id = ".$sesid."

UNION ALL

SELECT 'Chemical Technician' AS profession, 
       COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemical Technician', profession) THEN participant_id END) AS profession_count
FROM sampledb.participant 
INNER JOIN tblattendance ON participant_id = participant.id 
WHERE session_id = ".$sesid."

UNION ALL

SELECT 'Chemist' AS profession, 
       COUNT(DISTINCT CASE WHEN FIND_IN_SET('Chemist', profession) THEN participant_id END) AS profession_count
FROM sampledb.participant 
INNER JOIN tblattendance ON participant_id = participant.id 
WHERE session_id = ".$sesid."

UNION ALL

SELECT 'Others' AS profession, 
       COUNT(DISTINCT CASE WHEN profession = 'Others' THEN participant_id END) AS profession_count
FROM sampledb.participant 
INNER JOIN tblattendance ON participant_id = participant.id 
WHERE session_id = ".$sesid."

UNION ALL

SELECT 'None' AS profession, 
       COUNT(DISTINCT CASE WHEN profession = 'None' THEN participant_id END) AS profession_count
FROM sampledb.participant 
INNER JOIN tblattendance ON participant_id = participant.id 
WHERE session_id = ".$sesid.";
";
$all_session4 = $dbcon->query($sql4);

while($row = mysqli_fetch_array($all_session4)){
    echo "<td style=' border: 2px solid black;text-align: center;'>".$row['profession_count']."</td>";
}

$sql5 = "SELECT participant.age, COUNT(participant.age) AS count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id 
WHERE session_id =".$sesid."
GROUP BY participant.age
ORDER BY 
CASE 
WHEN participant.age = '61 and above' THEN 1
WHEN participant.age = '51-60' THEN 2
WHEN participant.age = '41-50' THEN 3
WHEN participant.age = '31-40' THEN 4
WHEN participant.age = '21-30' THEN 5
WHEN participant.age = 'Less than 21' THEN 6
WHEN participant.age =  '' THEN 7
ELSE 8
END;";

$all_session5 = $dbcon->query($sql5);
while($row = mysqli_fetch_assoc($all_session5)){
    echo "<td style=' border: 2px solid black;text-align: center;'>".$row['count']."</td>";
}
// Fetching the country count for the specific session
$sql6 = "SELECT Country, COUNT(Country) AS country_count 
         FROM tblattendance 
         WHERE session_id =".$sesid."
         GROUP BY Country 
         ORDER BY 
            CASE 
                WHEN Country = 'Philippines' THEN 1
                ELSE 2
            END, 
            Country;";

$all_session6 = $dbcon->query($sql6);
$country_count = $dbcon->query($sql_country); // Assuming $sql_country is already defined elsewhere

// Create an associative array for quick lookup of country counts
$country_counts = [];
while($row = mysqli_fetch_assoc($all_session6)) {
    $country_counts[$row['Country']] = $row['country_count'];
}

// Loop through the countries from the country list and display their counts
while($row = mysqli_fetch_assoc($country_count)){
    $country = $row['Country'];
    // Check if the country exists in the $country_counts array
    if(isset($country_counts[$country])) {
        echo "<td style='border: 2px solid black;text-align: center;'>".$country_counts[$country]."</td>";
    } else {
        // If country is not found in the result, display 0
        echo "<td style='border: 2px solid black;text-align: center;'>0</td>";
    }
}

$sql7 = "SELECT spkr_gender, COUNT(spkr_gender) AS 'count' 
         FROM tblspeakerses 
         WHERE session_id = ".$sesid." 
         GROUP BY spkr_gender 
         ORDER BY spkr_gender DESC;";

$all_session7 = $dbcon->query($sql7);

// Initialize total to 0
$total = 0;

// Create an associative array to store gender counts (initialize with 0 for 'Male' and 'Female')
$gender_counts = [
    'Male' => 0,
    'Female' => 0  // You can add more genders if needed
];

// Loop through the result set to populate gender counts
while ($row = mysqli_fetch_assoc($all_session7)) {
    // Store the count for each gender
    $gender_counts[$row['spkr_gender']] = (int)$row['count'];
}

// Calculate the total count
$total = array_sum($gender_counts);  // Sum up all the counts for the total

// Display the total first
echo "<td style='border: 2px solid black;text-align: center;'>".$total."</td>";

// Display the individual counts for Male, Female, and Other
foreach ($gender_counts as $gender => $count) {
    echo "<td style='border: 2px solid black;text-align: center;'>".$count."</td>";
}


}
?>


<!-- --------------------------------------------------------------------------------------------- -->



  </tr>
</table>

    <br>
    <br>
     </div>
    </div>
  </div>
















