<?php 
  $servername = "localhost";
  $username = "root";
  $password = "ptc@ptri@picts";
  $dbname = "sampledb";


$dbcon = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT COUNT(*) AS 'total' FROM tblptcsession";
$all_session = $dbcon->query($sql);

while($row = mysqli_fetch_assoc($all_session)){
        
        $count = $row['total'];
        

?>           
            <div class="col-md-4 b-2 graph" style="text-align: right;">
              
                <div class="graph_inner">
                  <h1>TOTAL SESSIONS</h1>
                  <div>

                  <div class="text-center align-middle" id="chart-container " >
                    <h2 class="timer count-title count-number" data-to="<?php echo ($count); ?>" data-speed="1500" style="font-size: 120px; font-family: melody;"></h2>
                  </div>
                </div>
              </div>
            </div>
          
        
<?php
}
?>