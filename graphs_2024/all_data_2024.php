<?php
/*
COUNT
AGE
GENDER
PROFESSION
LOCATION
*/
// Set the Content-Type to JSON
header('Content-Type: application/json');

// Include the database connection
require('../dbcon.php');

// Initialize session_id
$session_id = isset($_POST['sessionId']) ? (int)$_POST['sessionId'] : null;

// Check if sessionId is passed and modify the query accordingly
if ($session_id === null) {
    // Default query if session_id is not provided
    $sql_query_count = "
        SELECT count(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) as 'count' 
        FROM participant 
        WHERE datetime_registered 
        LIKE 
        '2024%' AND (
            (TRIM(birthdate LIKE '__/__/____')) OR 
            (TRIM(birthdate LIKE '%, ____')) OR 
            (TRIM(birthdate LIKE '% ____'))
        );";

    $sql_query_age = "
        SELECT
            Age,
            COUNT(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) AS 'count'
        FROM participant
        WHERE datetime_registered LIKE '2024%' 
          AND (
            TRIM(birthdate) LIKE '__/__/____' OR 
            TRIM(birthdate) LIKE '%, ____' OR 
            TRIM(birthdate) LIKE '% ____'
          )
        GROUP BY Age;";

    $sql_query_gender = "
    SELECT 
        Gender,
        COUNT(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) AS 'count'
    FROM participant
    WHERE datetime_registered 
            LIKE 
            '2024%' AND (
                (TRIM(birthdate LIKE '__/__/____')) OR 
                (TRIM(birthdate LIKE '%, ____')) OR 
                (TRIM(birthdate LIKE '% ____'))
            )
    GROUP BY Gender
    ORDER BY CASE 
    WHEN Gender = 'Male' THEN 1
    WHEN Gender = 'Female' THEN 2
    ELSE 3
    END;";

    $sql_query_profession = "
    SELECT 'Chemical Engineer' AS Profession, 
           COUNT(CASE WHEN 
              (LOWER(participant.profession) LIKE '%chemical engineer%' OR 
              LOWER(participant.profession) LIKE '%chemical engineering%' OR
              LOWER(participant.other_profession) LIKE '%chemical engineering%' OR 
              LOWER(participant.other_profession) LIKE '%chemical engineer%') THEN 1 END) AS Count
    FROM participant
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      )

    UNION ALL

    SELECT 'Chemical Technician' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%chemical technician%' THEN 1 END) AS Count
    FROM participant
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      )

    UNION ALL

    SELECT 'Chemist' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%chemist%' THEN 1 END) AS Count
    FROM participant
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      )

    UNION ALL

    SELECT 'Others' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%others%' AND 
              (LOWER(participant.other_profession) NOT LIKE '%chemical engineer%' AND 
               LOWER(participant.other_profession) NOT LIKE '%chemical engineering%') THEN 1 END) AS Count
    FROM participant
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      )

    UNION ALL

    SELECT 'None' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%none%' THEN 1 END) AS Count
    FROM participant 
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      );";

    $sql_query_location = "
   SELECT 
       countries.name AS Country,
       COUNT(DISTINCT CONCAT(firstName, ' ', middleName, ' ', lastName)) AS count
    FROM participant
    INNER JOIN world.countries ON countries.iso2 = participant.Country
    WHERE datetime_registered LIKE '2024%' 
      AND (
        TRIM(birthdate) LIKE '__/__/____' OR 
        TRIM(birthdate) LIKE '%, ____' OR 
        TRIM(birthdate) LIKE '% ____'
      )
    GROUP BY Country
    ORDER BY CASE 
    WHEN countries.name = 'Philippines' THEN 1
    ELSE 2
    END
    ";
} else {
    // Modify the query based on the session_id if provided
    $sql_query_count = "
    SELECT COUNT(DISTINCT participant_id) AS count FROM tblattendance WHERE session_id = ".$session_id.";
    ";

    $sql_query_age = "
    SELECT 
        participant.Age,
        COUNT(participant.Age) AS count
    FROM tblattendance 
    JOIN participant ON participant_id = participant.id
    WHERE session_id = " . $session_id . "
    GROUP BY Age;";

    $sql_query_gender = "
    SELECT 
       Gender, COUNT(Gender) as 'count'
       FROM tblattendance
       WHERE session_id = ".$session_id."
    GROUP BY Gender
    ORDER BY CASE 
    WHEN Gender = 'Male' THEN 1
    WHEN Gender = 'Female' THEN 2
    ELSE 3
    END;";

    $sql_query_profession = "
    SELECT 'Chemical Engineer' AS Profession, 
           COUNT(CASE WHEN 
              (LOWER(participant.profession) LIKE '%chemical engineer%' OR 
              LOWER(participant.profession) LIKE '%chemical engineering%' OR
              LOWER(participant.other_profession) LIKE '%chemical engineering%' OR 
              LOWER(participant.other_profession) LIKE '%chemical engineer%') THEN 1 END) AS Count
    FROM tblattendance 
    INNER JOIN participant ON tblattendance.participant_id = participant.id
    WHERE session_id = ".$session_id."

    UNION ALL

    SELECT 'Chemical Technician' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%chemical technician%' THEN 1 END) AS Count
    FROM tblattendance 
    INNER JOIN participant ON tblattendance.participant_id = participant.id
    WHERE session_id = ".$session_id."

    UNION ALL

    SELECT 'Chemist' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%chemist%' THEN 1 END) AS Count
    FROM tblattendance 
    INNER JOIN participant ON tblattendance.participant_id = participant.id
    WHERE session_id = ".$session_id."

    UNION ALL

    SELECT 'Others' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%others%' AND 
              (LOWER(participant.other_profession) NOT LIKE '%chemical engineer%' AND 
               LOWER(participant.other_profession) NOT LIKE '%chemical engineering%') THEN 1 END) AS Count
    FROM tblattendance 
    INNER JOIN participant ON tblattendance.participant_id = participant.id
    WHERE session_id = ".$session_id."

    UNION ALL

    SELECT 'None' AS Profession, 
           COUNT(CASE WHEN 
              LOWER(participant.profession) LIKE '%none%' THEN 1 END) AS Count
    FROM tblattendance 
    INNER JOIN participant ON tblattendance.participant_id = participant.id
    WHERE session_id = ".$session_id.";
    ;";

    $sql_query_location = "
    SELECT 
       Country,
       COUNT(Country) AS count
    FROM tblattendance
    WHERE session_id = ".$session_id."
    GROUP BY Country
    ORDER BY CASE 
    WHEN Country = 'Philippines' THEN 1
    ELSE 2
    END;
    ";
}

// Execute the query
$result_count = mysqli_query($dbcon, $sql_query_count);
$result_age = mysqli_query($dbcon, $sql_query_age);
$result_gender = mysqli_query($dbcon, $sql_query_gender);
$result_profession = mysqli_query($dbcon, $sql_query_profession);
$result_location = mysqli_query($dbcon, $sql_query_location);

if($row = mysqli_fetch_assoc($result_count)){
    $data['count'] = $row['count'];
}

$data['age'] = ['total' => 0];
while ($row = mysqli_fetch_assoc($result_age)) {
    $data['age'][$row['Age']] = (int)$row['count']; 
    $data['age']['total'] += (int)$row['count']; 
}

$data['gender'] = ['total' => 0];
while ($row = mysqli_fetch_assoc($result_gender)) {
    $data['gender'][$row['Gender']] = (int)$row['count'];
    $data['gender']['total'] += (int)$row['count'];
}

$data['profession'] = ['total' => 0];
while ($row = mysqli_fetch_assoc($result_profession)) {
    $data['profession'][$row['Profession']] = (int)$row['Count'];
    $data['profession']['total'] += (int)$row['Count'];
}

$data['location'] = ['total' => 0];
while ($row = mysqli_fetch_assoc($result_location)) {
    $data['location'][$row['Country']] = (int)$row['count'];
    $data['location']['total'] += (int)$row['count'];
}

// Return the data as a JSON response
echo json_encode($data);
?>
