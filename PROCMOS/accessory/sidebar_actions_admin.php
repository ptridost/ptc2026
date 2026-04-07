<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
	<a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" style="background-color: #343a40;">ACTIONS</a>
	<a id="pill_viewPR" class="nav-link actionPills" href="viewpr.php">Print/View PR</a>
	<a id="pill_editPR" class="nav-link actionPills" href="editpr.php">Edit PR</a>
	<?php
		if($_SESSION['AccountType'] == "End-User"){
			echo '<a id="pill_actionPR" class="nav-link actionPills" href="pr_enduseraction.php">End-User Action</a>';
		}
		else{
			// echo '<a id="pill_actionPR" class="nav-link actionPills" href="pr_signatureaction.php">For Action</a>';
		}

		if($_SESSION['AccountType'] == "PPGSS"){
			echo '
					<a id="pill_idPR" class="nav-link actionPills" href="pr_identification.php">PR Identification</a>
					<a id="pill_PRtimeline" class="nav-link actionPills" href="pr_timeline.php">PR Timeline</a>
					<a id="pill_PRcancellation" class="nav-link actionPills" href="pr_cancellation.php">PR Cancellation</a>
				';
		}
		else{
			//nothing
		}
	?>
</div>