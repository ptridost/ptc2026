<?php
// if(isset($_GET['token']) && $_GET['token'] =='969b331051cc464a84efdd7539179b09'){
	 
// } else{
// 	header("Location: ./index.php");
// }

require("./dbcon.php");
require("./dbcon_world.php");

?>


<!DOCTYPE html>
<html>
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./admin/admindashboard.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/footers.css">
    <link rel="stylesheet" type="text/css" href="./header&footer/Navbarstyle.css">
    <link rel="stylesheet" type="text/css" href="./analytics2024.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
	
	<script type="text/javascript" src="js/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.min.js" integrity="sha512-a6ctI6w1kg3J4dSjknHj3aWLEbjitAXAjLDRUxo2wyYmDFRcz2RJuQr5M3Kt8O/TtUSp8n2rAyaXYy1sjoKmrQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  

   	<title>Analytics for 2024</title>
  </head>
<body>

	<div class="container pt-5 mt-5 pb-5">
	  <div class="d-flex justify-content-center" role="group">
	    <!-- Left Button (Cycle Left) -->
	    <button class="custom_button_arrow left col-2" id="btnLeft">
	      <ion-icon name="chevron-back-outline"></ion-icon>
	    </button>

	    <!-- Dropdown -->
	    <div class="dropdown-center d-grid col-8">
	      <button class="custom_button dropdown-toggle text-wrap" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
	        Select Option
	      </button>
	      <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton" id="session_list">
	      	<li class="dropdown-item">2024</li>

	      	<?php
	      	$sql_sessions = "SELECT session_head, session_title, session_number FROM tblptcsession WHERE session_date LIKE '2024%' ORDER BY session_number;";
	      	$result = $dbcon->query($sql_sessions);
	      	while($row = mysqli_fetch_assoc($result)){
	      	    // Add custom data attributes to store session number and title
	      	    echo "<li class='dropdown-item' data-session-number='".$row['session_number']."' data-session-title='".$row['session_title']."'>".$row['session_head']."</li>";
	      	}
	      	?>

	      </ul>
	    </div>

	    <!-- Right Button (Cycle Right) -->
	    <button class="custom_button_arrow right col-2" id="btnRight">
	      <ion-icon name="chevron-forward-outline"></ion-icon>
	    </button>
	  </div>
	</div>



<!-- Custom JavaScript for cycling functionality -->
<script>
  $(document).ready(function() {
    const $dropdownList = $('.dropdown-menu');
    const $items = $dropdownList.find('.dropdown-item');
    let currentIndex = 0;

    // Function to update the displayed selected option
function updateSelection() {
  const selectedItem = $items.eq(currentIndex);
  const newText = selectedItem.text();

  // Slide up the current text, update the text, then slide down the new text
  $('#dropdownMenuButton').slideUp(200, function() {
    $(this).text(newText).slideDown(200); // Slide up, update text, slide down
  });
}


    // Left button click (cycle left)
    $('#btnLeft').click(function() {
      currentIndex = (currentIndex === 0) ? $items.length - 1 : currentIndex - 1;
      updateSelection();
      fetchData();
    });

    // Right button click (cycle right)
    $('#btnRight').click(function() {
      currentIndex = (currentIndex === $items.length - 1) ? 0 : currentIndex + 1;
      updateSelection();
      fetchData();
    });

    // Item click (select from dropdown list directly)
    $items.click(function() {
      currentIndex = $items.index(this);
      updateSelection();
      fetchData();
    });

    // Function to get the currently selected value (session number)
    function getSelectedValue() {
      const selectedItem = $items.eq(currentIndex);
      const sessionNumber = selectedItem.data('session-number');  // Retrieve session number
      const sessionTitle = selectedItem.data('session-title');    // Retrieve session title
      console.log("Session Number: " + sessionNumber + ", Session Title: " + sessionTitle);
      return sessionNumber;  // Or you can return sessionTitle if needed
    }

    // Function to perform AJAX and fetch data
    function fetchData() {
      var selectedValue = getSelectedValue();  // Get the session number

      $.ajax({
        url: './graphs_2024/all_data_2024.php',  // Replace with your PHP file path
        method: 'POST',
        data: {
          sessionId: selectedValue  // Send the selected session number to the server  	
        },
        dataType: 'json',  // Expecting JSON response
        success: function(response) {
          // Check if the response is valid and contains the data we need
          if (response) {
            // Assuming the response contains the age data
            var data = response;
						
            // Dynamically fill the table with the data
            if(selectedValue != undefined){
            	$('th#data_count_title').text('Participants Attended');
            	$('#count_graph').text('Participants Attended');
            	$('#count_graph_subtext').text('');
            }else{
            	$('th#data_count_title').text('2024 Registrants');
            	$('#count_graph').text('New Registrants');
            	$('#count_graph_subtext').text('as of 2024');
            }
            $('td#data_count').text(data['count']);
            $('#count_graph_value').text(data['count']);
            var age_title = "";
            var age_data = "";
            for(const[key,value] of Object.entries(data.age)){
            	if (key == 'total'){
            		continue;
            	}
            	age_title += "<td>"+ `${key}` +"</td>";
            	age_data += "<td>"+ `${value}` +"</td>";
            }

            var gender_title = "";
            var gender_data = "";
            for(const[key,value] of Object.entries(data.gender)){
            	if (key == 'total'){
            		continue;
            	}
            	gender_title += "<td>"+ `${key}` +"</td>";
            	gender_data += "<td>"+ `${value}` +"</td>";
            }

            var profession_title = "";
            var profession_data = "";
            for(const[key,value] of Object.entries(data.profession)){
            	if (key == 'total'){
            		continue;
            	}
            	profession_title += "<td>"+ `${key}` +"</td>";
            	profession_data += "<td>"+ `${value}` +"</td>";
            }

            var location_title = "";
            var location_data = "";
            for(const[key,value] of Object.entries(data.location)){
            	if (key == 'total'){
            		continue;
            	}
            	location_title += "<td>"+ `${key}` +"</td>";
            	location_data += "<td>"+ `${value}` +"</td>";
            }

            $('#age').attr('colspan',Object.keys(data.age).length-1);
            $('#gender').attr('colspan',Object.keys(data.gender).length-1);
          
            $('tr#data_age_gender_title_row').empty().append(age_title + gender_title);
            $('tr#data_profession_title_row').empty().append(profession_title);
            $('tr#data_location_title_row').empty().append(location_title);
            $('tr#data_age_gender_row').empty().append(age_data + gender_data);
            $('tr#data_profession_row').empty().append(profession_data);
            $('tr#data_location_row').empty().append(location_data);

            // GRAPHS
						genderChart(data.gender);
						ageChart(data.age);
						professionChart(data.profession);
						locationChart(data.location);
          } else {
            // Handle cases where the response is invalid or doesn't contain the expected data
            alert('Error: Data not received correctly');
          }
        },
        error: function(xhr, status, error) {
          // Handle any AJAX errors
          console.error('AJAX error: ' + status + ', ' + error);
          alert('Failed to fetch data.');
        }
      });
    }

    // Initial selection display
    updateSelection();

    // Fetch data on page load based on initial selection
    fetchData();
  });
</script>

	 <div class="d-flex justify-content-center">
	 	<button class="toggle__tables btn btn-danger btn-dark-blue ">Click here to view tabular data</button>
	 </div>
	 

	 <script type="text/javascript">
	 	$(document).ready(function(){
	 		$(".tables").hide();
	 		$(".toggle__tables").click(function(){
	 			$(".tables").toggle("slow","swing");
	 		});
	 	})
	 	
	 </script>

		<div class="container pt-4 tables text-center" id="table_container">
		<div class="table-responsive">
			<table class="table table-bordered count">
				<tr>
					<th id="data_count_title" colspan=100%>Loading...</th>
				</tr>
				<tr>
					<td id="data_count" colspan="100%">Loading...</td>
				</tr>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered table-sm">
				<tr>
					<th id="age">Age</th>
					<th id="gender">Gender</th>
				</tr>
				<tr id="data_age_gender_title_row">
				</tr>
				<tr id="data_age_gender_row">
				</tr>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th id="profession" colspan="100%">Profession</th>
				</tr>
				<tr id="data_profession_title_row">
				</tr>
				<tr id="data_profession_row"></tr>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered">
				<tr>
					<th id="location" colspan="100%">Location</th>
				</tr>
				<tr id="data_location_title_row">
				</tr>
				<tr id="data_location_row"></tr>
			</table>
		</div>
		
	</div>
	<div class="container py-5">
		<div class="row">

	  <div class="col-md-4 graph container mt-5">
	      <div class="graph_inner py-auto">
	        <h1 id="count_graph">Loading...</h1>
	        <h6 class="fst-italic" id="count_graph_subtext"></h6>
	        <div>
	      
	          <div class="text-center align-middle" id="main-box">
	          <h2 class="count-title count-number fw-bold" id="count_graph_value" style="font-size: 120px; font-family:MelodyBold;"></h2>
	        </div>
	       
	      </div>
	    </div>
	</div>

	
				<!-- GENDER -->
				<div class="col-md-4 graph">
			    <div class="graph_inner">
			        <h1 class="py-4">Gender</h1>
			        <div id="chart-container" style="width: 100%; height: 100%; padding-left: 1em; padding-right: 1em;">
			            <canvas id="graphCanvasGender" height="380" width="550"></canvas>
			        </div>

			        <script>
			     
			           var genderChartInstance = null; // This will hold the chart instance globally

			           function genderChart(data) {
			               var marks = [];
			               var keys = [];

			               // Assuming data is an array of key-value pairs where the key is the gender and value is the count
			               for (var [key, value] of Object.entries(data)) {
			                   if (key !== 'total') { // Skip 'total' if it exists
			                       keys.push(key); // Gender labels
			                       marks.push(value); // Gender counts
			                   }
			               }

			               // Chart data configuration
			               var chartdata = {
			                   labels: keys, // Gender categories
			                   datasets: [{
			                       label: "Gender Distribution", // Label for the dataset
			                       backgroundColor: ['rgb(30,144,255)', 'rgb(255,105,180)', 'rgb(186,85,211)'], // Colors for each gender
			                       borderColor: ['rgb(30,144,255)', 'rgb(255,105,180)', 'rgb(186,85,211)'], // Border colors for each gender
			                       borderWidth: 1,
			                       data: marks // Values corresponding to each gender
			                   }]
			               };

			               var graphTarget = $("#graphCanvasGender");

			               // Check if the chart already exists and update it
			               if (genderChartInstance) {
			                   genderChartInstance.data = chartdata; // Update the data
			                   genderChartInstance.update();  // Update the chart
			               } else {
			                   genderChartInstance = new Chart(graphTarget, {
			                       type: 'pie', // Pie chart
			                       data: chartdata   // Chart data to display
			                   });
			               }
			           }

			        </script>
			    </div>
			</div>


				<!-- Age -->

				<div class="col-md-4 graph mb-5">
				  <div class="graph_inner">
				  <h1 class="pb-4">Age</h1>
				 	<div id="chart-container" style = " width: 100%;height: 100%; padding-left: 1em; padding-right: 1em;">
				     <canvas id="graphCanvasAge" height="380" width="500"></canvas>
				  </div>
				      <script>
				      	var ageChartInstance = null;

				      	function ageChart(data) {
				      	    var marks = [];
				      	    var keys = [];

				      	    // Assuming data is an array of key-value pairs where the key is the age group and value is the count
				      	    for (var [key, value] of Object.entries(data)) {
				      	        if (key !== 'total') { // Skip 'total' if it exists
				      	            keys.push(key); // Age group labels
				      	            marks.push(value); // Age group counts
				      	        }
				      	    }

				      	    var chartdata = {
				      	        labels: keys, // Age groups
				      	        datasets: [{
				      	            label: "", // Optional: can be customized
				      	            backgroundColor: '#49e2ff',
				      	            borderColor: '#46d5f1',
				      	            hoverBackgroundColor: '#CCCCCC',
				      	            hoverBorderColor: '#666666',
				      	            data: marks,
				      	            backgroundColor: [
				      	                'rgb(255,69,0)',   // Color for each segment
				      	                'rgb(255,140,0)',
				      	                'rgb(255,240,0)',
				      	                'rgb(50,205,50)',
				      	                'rgb(30,144,255)',
				      	                'rgb(186,85,211)',
				      	                'rgb(83,104,120)',
				      	            ],
				      	            borderColor: [
				      	                'rgb(255,69,0)',   // Border color for each segment
				      	                'rgb(255,140,0)',
				      	                'rgb(255,240,0)',
				      	                'rgb(50,205,50)',
				      	                'rgb(30,144,255)',
				      	                'rgb(186,85,211)',
				      	                'rgb(83,104,120)',
				      	            ],
				      	            hoverOffset: 4
				      	        }]
				      	    };

				      	    var graphTarget = $("#graphCanvasAge");

				      	    // If the chart already exists, update it
				      	    if (ageChartInstance) {
				      	        ageChartInstance.data = chartdata; // Update the data
				      	        ageChartInstance.update(); // Update the chart
				      	    } else {
				      	        // If the chart doesn't exist, create a new one
				      	        ageChartInstance = new Chart(graphTarget, {
				      	            type: 'doughnut', // Doughnut chart type
				      	            data: chartdata   // Chart data to be displayed
				      	        });
				      	    }
				      	}

					</script>
				</div>
		</div>
		
	<!-- Profession -->
	<div class="col-md-12 graph mb-5">
	    <div class="graph_inner">
	      <h1>Profession</h1>
	      <div id="chart-container" style = " position: relative; width: 100%;
	                    height: 100%; padding-right: 5em; margin-top: 1em; margin-bottom:-9rem">
	          <canvas id="graphCanvasProfession" height="210" width="500"></canvas>
	        </div>

	     <script>	
	     		var professionChartInstance = null;        
	        function professionChart(data)
	        {
	            
	                    console.log(data);
	                    var marks = [];
	                    var	keys =[]
	                    

	                    for (var [key, value] of Object.entries(data)) {
	                      
	                      if(key != 'total'){
	                          marks.push(value);
	                          keys.push(key);
	                      }
	                        
	                    }
	                      
	                    const chartdata = {

	                      labels: keys,
	                  
	                     
	                        datasets: [
	                            { 

	                              label: "" ,
	                                backgroundColor: '#49e2ff',
	                                borderColor: '#46d5f1',
	                                hoverBackgroundColor: '#CCCCCC',
	                                hoverBorderColor: '#666666',
	                                data: marks,
	                                backgroundColor: [
	                                    
	                                    'rgb(255, 206, 86)',
	                                    'rgb(4, 15, 111)',
	                                    'rgb(153, 102, 255)',
	                                    'rgb(255, 159, 64)',
	                                    'rgb(255,69,0)',
	                                    'rgb(83,104,120)',
	                                ],
	                                borderColor: [
	                                    
	                                    'rgb(255, 206, 86)',
	                                    'rgb(75, 192, 192 )',
	                                    'rgb(153, 102, 255 )',
	                                    'rgb(255, 159, 64 )',
	                                    'rgb(255,69,0)',
	                                    'rgb(83,104,120)',
	                                ],
	                                hoverOffset: 4,
	                                
	                            }
	                        ]

	                    };

	                    var graphTarget = $("#graphCanvasProfession");

	                    // If the chart is already created, update it, otherwise create a new one
	                    if (professionChartInstance) {
	                        professionChartInstance.data = chartdata;
	                        professionChartInstance.update();
	                    } else {
	                        professionChartInstance = new Chart(graphTarget, {
	                            type: 'bar', 
	                            data: chartdata // Data to be displayed on the chart
	                        });
	                    }		                
	        }
	        
	    </script>

	        <div class="Profession_graph_color">
	        
	      </div>
	  </div>
	</div>
	<!-- Location -->
	   <div class="col-md-12 graph" style="margin-top: 10px;">
	    <div class="graph_inner">
	      <h1 class="py-4">Location</h1>
	      <div id="chart-container" style = " position: relative; width: 100%; margin-top: 2em; margin-bottom:-9rem">
	          <canvas id="graphCanvasLocation" height="125" width="500"></canvas>
	        </div>

	     <script>
	        var locationChartInstance = null;
	        function locationChart(data) {
	  
	        console.log(data);
	        var marks = [];
	        var keys =[];
	        for (var [key, value] of Object.entries(data)) {

	            if (key !== 'total') {
	                marks.push(value);
	                keys.push(key);
	            }
	        }
	        function generateColor(array_data) {
	            const color_array = [];
	            let i = 0;
	            while (i < array_data.length) {
	                // Generate a random hue, keeping saturation and lightness fixed at 100% and 70%
	                const randomHue = Math.floor(Math.random() * 360); // Random number between 0 and 360 for the hue
	                color_array.push(`hsla(${randomHue}, 100%, 70%, 1)`); // Add the generated color to the array
	                i++;
	            }
	            return color_array;
	        }
	        var chartdata = {
	            labels: keys,
	            datasets: [
	                {
	                    label:"",
	                    backgroundColor: '#49e2ff',
	                    borderColor: '#46d5f1',
	                    hoverBackgroundColor: '#CCCCCC',
	                    hoverBorderColor: '#000000',
	                    data: marks,
	                    backgroundColor: generateColor(marks),
	                   
	                    hoverOffset: 1,
	                }
	            ]
	        };

	        var graphTarget = $("#graphCanvasLocation");

	        if (locationChartInstance) {
	            locationChartInstance.data = chartdata;
	            locationChartInstance.update();
	        } else {
	           locationChartInstance = new Chart(graphTarget, {
	                type: 'bar', 
	                data: chartdata, // Data to be displayed on the chart
	                options: {
	                   scales: {
	                       yAxes: [{
	                           ticks: {
	                               stepSize: 5, // Step size between ticks
                                   min: 0, // Minimum value of Y-axis
                                   max: 20, // Maximum value of Y-axis (set it according to your data)
                                   callback: function(value) {
                                   		if (value == 20){
                                   			value = Math.max(...marks)
                                   		}
                                       return value; // Adding unit label to Y-axis ticks
                                   }
	                           }
	                       }]
	                   },
	                   legend:{
	                   	display:false
	                   },
	                   tooltips:{
	                   		mode:'point'
	                   }
	               }
	            });
	        }			    
	}

	        
	    </script>
	        <div class="Profession_graph_color">
	        
	      </div>
	  </div>
	</div>

	    </div>
	    </div>
	</div>   
	<?php  include("header&footer/navbar.php" );?>
   <?php  include("header&footer/footer.php" );?>
</body>
</html>



