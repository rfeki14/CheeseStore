<header class="main-header">
  <!-- Link to External CSS -->
    <link rel="stylesheet" href="dist/css/navbar.css">


  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <!-- Logo -->
      <a href="index.php" class="navbar-brand">
       
        <img src="images/logo.png" width="50" height="50" alt="ShopMate Logo">

      </a>

      <!-- Mobile Navbar Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>

      <!-- Navbar Items -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
          <li class="nav-item"><a class="nav-link" href="listeproduct.php">Products</a></li>

          <!-- Categories Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown">
              Categories
            </a>
            <ul class="dropdown-menu">
              <?php
              $conn = $pdo->open();
              try {
                $stmt = $conn->prepare("SELECT * FROM category");
                $stmt->execute();
                foreach ($stmt as $row) {
                  echo "<li><a class='dropdown-item' href='category.php?category=".$row['cat_slug']."'>".$row['name']."</a></li>";
                }
              } catch(PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
              }
              $pdo->close();
              ?>
            </ul>
          </li>
        </ul>

        <!-- Search Bar -->
        <form method="POST" class="d-flex" action="search.php">
          <div class="input-group">
            <input type="text" class="form-control" name="keyword" placeholder="Search..." required>
            <button class="btn btn-light" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </form>

        <!-- Right Section: Cart & User Profile -->
        <ul class="navbar-nav ms-3">
          <!-- Cart Icon -->
          <li class="nav-item position-relative">
            <a href="cart_view.php" class="nav-link">
              <i class="fa fa-shopping-cart fa-lg"></i>
              <span class="cart_count">0</span>
            </a>
          </li>

          <!-- User Profile Dropdown -->
          <?php if(isset($_SESSION['user'])): ?>
            <?php $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <img src="<?= $image ?>" width="30" height="30"> <?= $user['firstname'] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="logout.php">Sign Out</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="signup.php">Signup</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>
