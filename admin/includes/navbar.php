<header class="main-header">
  <!-- Logo -->
  <a href="#" class="logo"style="background-color: #2c3e50;">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini" style="background-color: #2c3e50;"><b>C</b>B</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg" style="background-color: #2c3e50;"><b>Cœur</b><b> Blanc</b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" style="background-color: #2c3e50;">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu" style="background-color: #2c3e50;">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo (!empty($admin['photo'])) ? '../images/'.$admin['photo'] : '../images/profile.jpg'; ?>" class="user-image" alt="User Image">
            <span class="hidden-xs"><?php echo $admin['firstname'].' '.$admin['lastname']; ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <img src="<?php echo (!empty($admin['photo'])) ? '../images/'.$admin['photo'] : '../images/profile.jpg'; ?>" class="img-circle" alt="User Image">

              <p>
                <?php echo $admin['firstname'].' '.$admin['lastname']; ?>
                <small>Member since <?php echo date('M. Y', strtotime($admin['created_on'])); ?></small>
              </p>
            </li>
            <li class="user-footer" style="background-color: #f8f9fa; display: flex; justify-content: space-around; padding: 10px;">
              <div>
                <a href="#profile" data-toggle="modal" class="btn btn-link" id="admin_profile" title="Edit Profile">
                  <i class="fas fa-user-edit fa-lg" style="color: #007bff; transition: transform 0.2s;"></i>
                </a>
              </div>
              <div>
                <a href="../logout.php" class="btn btn-link" title="Sign Out">
                  <i class="fas fa-sign-out-alt fa-lg" style="color: #dc3545; transition: transform 0.2s;"></i>
                </a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<?php include 'includes/profile_modal.php'; ?>