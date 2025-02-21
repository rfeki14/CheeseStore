<?php include 'includes/session.php'; ?>
<?php
  if(!isset($_GET['code']) OR !isset($_GET['user'])){
    header('location: index.php');
    exit(); 
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
      <h2 class="text-primary mb-3">Reinitialiser votre mot de passe</h2>

      <form action="password_new.php?code=<?php echo $_GET['code']; ?>&user=<?php echo $_GET['user']; ?>" method="POST">
        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="New Password" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="repassword" placeholder="Re-type Password" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="reset"><i class="fa fa-check-square-o"></i> Reset Password</button>
      </form>

      <div class="login-links">
        <a href="login.php">Retour à la connexion</a>
        <a href="index.php"><i class="fa fa-home"></i> Retour à l'accueil</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
