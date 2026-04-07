<?php 
    include_once 'database.php';
    $result = mysqli_query($dbcon, "SELECT * FROM contact");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

     <link rel="stylesheet" href="retrieve.css">
    
    <title>Contact Table</title>
</head>
<body>

        <div class="container">
            <div class="row mt-5">
                <div class="col">
                    	<div class="mt-5">
                    		<center><h2>Contact Table</h2></center>
                        </div>
                        <br><br>

                        	<table class="table table-bordered">
                            <tr class="items">
                                <td> First Name </td>
                                <td> Last Name </td>
                                <td> Email </td>
                                <td> Contact Number </td>
                                <td> Message </td>
                               
                            </tr>
                            
                            <tr>
                            
                            <?php 

                            	while($row = mysqli_fetch_array($result))
                            	{
                            ?>
                            		
                            		<td><?php echo $row['FirstName']; ?></td>
                                    <td><?php echo $row['LastName']; ?></td>
                                    <td><?php echo $row['Email']; ?></td>
                                    <td><?php echo $row['ContactNumber']; ?></td>
                                    <td><?php echo $row['Message']; ?></td>
                                   

                            </tr>

                            <?php
                        	}

                        	?>
                                 

                        </table>
                    </div>
                </div>
            </div>
       
</body>
</html>