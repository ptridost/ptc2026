<?php
header('Content-Type: application/json');
$conn = mysqli_connect("localhost", "root", "ptc@ptri@picts", "sampledb");

$sqlQuery = "
SELECT 'Chemical Engineer' AS Profession, 
       COUNT(DISTINCT CASE WHEN 
          (LOWER(participant.profession) LIKE '%chemical engineer%' OR 
           LOWER(participant.profession) LIKE '%chemical engineering%' OR
           LOWER(participant.other_profession) LIKE '%chemical engineering%' OR 
           LOWER(participant.other_profession) LIKE '%chemical engineer%') THEN tblattendance.participant_id END) AS Count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id

UNION ALL

SELECT 'Chemical Technician' AS Profession, 
       COUNT(DISTINCT CASE WHEN 
          LOWER(participant.profession) LIKE '%chemical technician%' THEN tblattendance.participant_id END) AS Count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id

UNION ALL

SELECT 'Chemist' AS Profession, 
       COUNT(DISTINCT CASE WHEN 
          LOWER(participant.profession) LIKE '%chemist%' THEN tblattendance.participant_id END) AS Count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id

UNION ALL

SELECT 'Others' AS Profession, 
       COUNT(DISTINCT CASE WHEN 
          LOWER(participant.profession) LIKE '%others%' AND 
          (LOWER(participant.other_profession) NOT LIKE '%chemical engineer%' AND 
           LOWER(participant.other_profession) NOT LIKE '%chemical engineering%') THEN tblattendance.participant_id END) AS Count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id

UNION ALL

SELECT 'None' AS Profession, 
       COUNT(DISTINCT CASE WHEN 
          LOWER(participant.profession) LIKE '%none%' THEN tblattendance.participant_id END) AS Count
FROM tblattendance 
INNER JOIN participant ON tblattendance.participant_id = participant.id;
";

$result = mysqli_query($conn, $sqlQuery);

while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['Profession']] = $row['Count'];
}

mysqli_close($conn);
echo json_encode([$data]);
?>
