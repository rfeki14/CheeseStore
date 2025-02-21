<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }
?>
<?php include 'includes/header.php'; ?>

<!-- Lien vers le CSS externe -->
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
      <h2 class="text-primary mb-3">Connectez-vous à votre compte</h2>

      <form action="verify.php" method="POST">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Email" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="login"><i class="fa fa-sign-in"></i> Se connecter</button>
      </form>

      <div class="login-links">
        <a href="password_forgot.php">Mot de passe oublié ?</a>
        <a href="signup.php">Créer un nouveau compte</a>
        <a href="index.php"><i class="fa fa-home"></i> Retour à l'accueil</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>