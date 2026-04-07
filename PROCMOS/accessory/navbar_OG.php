<nav class="navbar navbar-light navbar-expand-md nav-bg">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Procurement & Property Management Information System</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li> -->
        </ul>
    </div>
    <div class="mx-auto order-0">
        <a class="navbar-brand mx-auto" href="#">PPMIS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
          <!-- <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li> -->
          <li id="liAPP" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Annual Procurement Plan
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="../operations/viewAPP.php"><i class="fas fa-file-contract"></i> VIEW A.P.P. </a>
              <a class="dropdown-item" href="app.php"><i class="fas fa-columns"></i> DASHBOARD</a>
            </div>
          </li>
          <li id="liPR" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Purchase Request
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="purchase_request.php"><i class="fas fa-shopping-basket"></i></i> CREATE P.R. </a>
              <a class="dropdown-item" href="dashboard.php"><i class="fas fa-columns"></i> DASHBOARD</a>
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