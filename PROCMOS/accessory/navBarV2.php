<nav class="navbar navbar-expand-md nav-bg navbar-dark animated slideInDown" style="background-color: #00aeef;">
  <!-- Brand -->
  <!-- Redirect /PTRI_SRRIS/veer/ http://services.dost-ptri-rypic.com/pages/index.php -->
  <a class="navbar-brand navBar-logo" href="#" style="padding-top: 0; padding-bottom: 0; margin-right: 0.4rem"><img width="50px" src="../photos/ptrionlywhite.png">
    </a>

    <a class="navbar-brand navBar-GovName" href="#" style="font-size: 14px; padding-top: 0; padding-bottom: 0; line-height: 1.2">
      <span>Department of Science and Technology</span>
      <br>
      <span><b>PHILIPPINE TEXTILE RESEARCH INSTITUTE</b></span>
    </a>

    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
      		<li class="nav-item active">
	            <a class="nav-link" href="#">Dashboard</a>
	        </li>
	        <li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Modules
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink" style="z-index: 999">
				  	<a class="dropdown-item" href="#">APP</a>
				  	<a class="dropdown-item" href="#">Purchase Request</a>

				  	<a class="dropdown-item" href="#">RFQ</a>
				  	<a class="dropdown-item" href="#">AOC</a>
				  	<a class="dropdown-item" href="#">BAC Reso</a>
				  	<a class="dropdown-item" href="#">Purchase Order</a>
				  <!-- <a id="lnkChangePW" class="dropdown-item" data-toggle="modal" href="#modalUpdateTempPW"><i class="fas fa-key"></i> Change Password</a> -->
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<?php echo $_SESSION['Name']?>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
				  	<a class="dropdown-item" href="#"><i class="fas fa-id-badge"></i> <?php echo $_SESSION['Username'];?></a>
				  <!-- <a id="lnkChangePW" class="dropdown-item" data-toggle="modal" href="#modalUpdateTempPW"><i class="fas fa-key"></i> Change Password</a> -->
			    	<a class="dropdown-item" href="../operations/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
				</div>
			</li>
        </ul>
    </div>
</nav>