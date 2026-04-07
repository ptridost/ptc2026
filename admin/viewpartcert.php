
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
 <link rel="stylesheet" type="text/css" href="admindashboard.css"> 

  <title>View Certificate</title>

  
  
  
  
<style>
@import url(https://fonts.googleapis.com/css?family=Roboto:100,300,400);
body {
  background: linear-gradient(to left, #3e94ec, #25b7c4);
  font-family: "Roboto", Helvetica, arial, sans-serif;
  text-rendering: optimizeLegibility;
}

h3 {
  width: 600px;
  display: block;
  margin: 60px auto 30px auto;
  color: #fafafa;
  font-size: 30px;
  font-weight: 400;
  font-style: normal;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
  text-transform: uppercase;
}

tbody tr:nth-child(0) {
  animation-delay: 0s;
}

tbody tr:nth-child(1) {
  animation-delay: 0.15s;
}

tbody tr:nth-child(2) {
  animation-delay: 0.3s;
}

tbody tr:nth-child(3) {
  animation-delay: 0.45s;
}

tbody tr:nth-child(4) {
  animation-delay: 0.6s;
}

tbody tr:nth-child(5) {
  animation-delay: 0.75s;
}

tbody tr:nth-child(6) {
  animation-delay: 0.9s;
}

tbody tr:nth-child(7) {
  animation-delay: 1.05s;
}

tbody tr:nth-child(8) {
  animation-delay: 1.2s;
}

tbody tr:nth-child(9) {
  animation-delay: 1.35s;
}

tbody tr:nth-child(10) {
  animation-delay: 1.5s;
}

tbody tr:nth-child(11) {
  animation-delay: 1.65s;
}

tbody tr:nth-child(12) {
  animation-delay: 1.8s;
}

tbody tr:nth-child(13) {
  animation-delay: 1.95s;
}

tbody tr:nth-child(14) {
  animation-delay: 2.1s;
}

tbody tr:nth-child(15) {
  animation-delay: 2.25s;
}

tbody tr:nth-child(16) {
  animation-delay: 2.4s;
}

tbody tr:nth-child(17) {
  animation-delay: 2.55s;
}

tbody tr:nth-child(18) {
  animation-delay: 2.7s;
}

tbody tr:nth-child(19) {
  animation-delay: 2.85s;
}

table {
  width: 600px;
  margin: 0 auto;
  border-collapse: collapse;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
}
table thead {
  border-bottom: 1px solid #444;
}
table thead th:first-child {
  border-top-left-radius: 5px;
}
table thead th:last-child {
  border-top-right-radius: 5px;
  border-right: none;
}
table tbody tr:last-child td {
  border-bottom: 0px;
}
table tbody tr:last-child td:first-child {
  border-bottom-left-radius: 5px;
}
table tbody tr:last-child td:last-child {
  border-bottom-right-radius: 5px;
}
table tbody tr {
  opacity: 0;
  animation: table;
  animation-duration: 1.5s;
  animation-fill-mode: forwards;
  background: #FFF;
  border-top: 1px solid #C1C3D1;
}
table tbody tr:nth-child(odd) {
  background: #EBEBEB;
}
table tbody tr td {
  transition: all 0.2s ease-in-out;
}
table tbody tr:hover td {
  background: #4E5066;
  color: #FFFFFF;
  border-top: 1px solid #22262e;
  border-bottom: 1px solid #22262e;
}
table th {
  font-weight: normal;
  text-align: left;
  background: #1b1e24;
  color: #D5DDE5;
  font-weight: 100;
  font-size: 23px;
  border-bottom: 4px solid #9ea7af;
  border-right: 1px solid #343a45;
  padding: 24px;
}
table td {
  padding: 20px;
  text-align: left;
  font-size: 18px;
  font-weight: 300;
  color: #666B85;
  text-shadow: 0 1px 1px rgba(255, 255, 255, 0.1);
  border-top: 1px solid #C1C3D1;
  border-bottom: 1px solid #C1C3D1;
}
table td:first-child {
  text-align: center;
}
table td:first-child:before {
  content: "# ";
}

@keyframes table {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
  
</head>

<body translate="col-md-Z no">
  <h3>Participant with Certificate</h3>

<?php
 $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

?>


<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Gender</th>
      <th>Country</th>
      <th>Citizenship</th>
      <th>Age</th>
      <th>Email</th>
      <th>Birthdate</th>
      <th>Profession</th>
      <th>Company</th>
      <th>Position</th>

    </tr>
  </thead>
  <tbody>
  <?php $dbcon = mysqli_connect("localhost","root","ptc@ptri@picts","sampledb") or die(mysqli_error()); ?>
   <?php 
  
    $sql_rc = mysqli_query($dbcon, "SELECT t2.participant_id, t2.Fname, session_head, profession, t3.id, t3.firstName, t3.middleName, t3.lastName, t3.Gender, t3.Country, t3.Citizenship, t3.Age, t3.username, t3.birthdate, t3.profession, t3.organization, t3.position FROM tblattendance t1 INNER JOIN tblfeedback t2 ON t1.participant_id = t2.participant_id AND t2.session_id = t1.session_id INNER JOIN participant t3 ON t3.id = t1.participant_id GROUP BY t1.participant_id HAVING COUNT(t3.id) > 5");

        while($sql_rc_row = mysqli_fetch_array($sql_rc)){

          ?>
    <tr>
      <td><?php echo $sql_rc_row['id']; ?></td>
      <td><?php echo $sql_rc_row['firstName']; ?> <?php echo $sql_rc_row['middleName']; ?> <?php echo $sql_rc_row['lastName']; ?></td>
      <td><?php echo $sql_rc_row['Gender']; ?></td>
      <td><?php echo $sql_rc_row['Country']; ?></td>
      <td><?php echo $sql_rc_row['Citizenship']; ?></td>
      <td><?php echo $sql_rc_row['Age']; ?></td>
      <td><?php echo $sql_rc_row['username']; ?></td>
      <td><?php echo $sql_rc_row['birthdate']; ?></td>
      <td><?php echo $sql_rc_row['profession']; ?></td>
      <td><?php echo $sql_rc_row['organization']; ?></td>
      <td><?php echo $sql_rc_row['position']; ?></td>
      

    </tr>
<?php
}
?>
  </tbody>
</table>
  
  
  
</body>

</html>
