<!-- 
7 - Navbar with brand left, links right, toggler right
    The links collapse into the mobile menu
-->
<nav class="navbar navbar-expand-md bg-dark navbar-dark" style="">
    <a href="#" class="navbar-brand"><img width="50px" src="../photos/ptrionlywhite.png"></a>
    <a class="navbar-brand" href="#" style="font-size: 14px; padding-top: 0; padding-bottom: 0; line-height: 1.2">
      <span>Department of Science and Technology</span>
      <br>
      <span><b>PHILIPPINE TEXTILE RESEARCH INSTITUTE</b></span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar7">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse justify-content-stretch" id="navbar7">
        <ul class="navbar-nav ml-auto">
            <li id="linav_Feedback" class="nav-item navLinks">
              <a class="nav-link" href="#">Admin</a>
          </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo $_SESSION['Name']?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#"><i class="fas fa-id-badge"></i> <?php echo $_SESSION['Username'];?></a>
            <a id="lnkChangePW" class="dropdown-item d-none" data-toggle="modal" href="#modalUpdateTempPW"><i class="fas fa-key"></i> Change Password</a>
            <a class="dropdown-item" href="../operations/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </li>
        </ul>
    </div>
</nav>


