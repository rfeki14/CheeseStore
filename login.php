<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }
?>
<?php include 'includes/header.php'; ?>

<!-- Link to External CSS -->
<link rel="stylesheet" href="dist/css/login.css">


<body class="hold-transition login-page">
  <div class="overlay"></div>

  <div class="login-box">
    <?php
      if(isset($_SESSION['error'])){
        echo "<div class='callout callout-danger'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
      }
      if(isset($_SESSION['success'])){
        echo "<div class='callout callout-success'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
      }
    ?>

    <div class="login-box-body">
      <h2 class="text-primary mb-3">Sign in to Your Account</h2>

      <form action="verify.php" method="POST">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="login"><i class="fa fa-sign-in"></i> Sign In</button>
      </form>

      <div class="login-links">
        <a href="password_forgot.php">Forgot your password?</a>
        <a href="signup.php">Register a new account</a>
        <a href="index.php"><i class="fa fa-home"></i> Back to Home</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
