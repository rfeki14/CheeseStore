<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
 <link rel="stylesheet" href="dist/css/password_forgot.css">
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
      <h2 class="text-primary mb-3">Reset Your Password</h2>
      <p class="text-muted">Enter your email address to reset your password</p>

      <form action="reset.php" method="POST">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="reset"><i class="fa fa-mail-forward"></i> Send Reset Link</button>
      </form>

      <div class="login-links">
        <a href="login.php">I remember my password</a>
        <a href="index.php"><i class="fa fa-home"></i> Back to Home</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
