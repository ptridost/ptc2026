<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
	<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" style="background-color: #343a40;">ACTIONS</a>
	<a id="pill_viewPR" class="nav-link actionPills" href="view_aoc.php">
	Print/View AOC</a>
	<a id="pill_actionPR" class="nav-link actionPills" href="attach_aoc.php">For Action</a>
	<?php
		// if($_SESSION['AccountType'] == "PPGSS-AOC"){
		// 	echo '<a id="pill_actionPR" class="nav-link actionPills" href="attach_aoc.php">For Action</a>';
			
		// }
		// else if($_SESSION['AccountType'] == "PPGSS-RFQ"){
		// 	//do nothing
		// 	// echo '<a id="pill_editPR" class="nav-link actionPills" href="attach_rfq.php">For RFQ</a>';
		// }
	?>
</div>