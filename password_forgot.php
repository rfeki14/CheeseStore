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
      <h2 class="text-primary mb-3">Réinitialiser votre mot de passe</h2>
      <p class="text-muted">Entrez votre adresse e-mail pour réinitialiser votre mot de passe</p>

      <form action="reset.php" method="POST">
        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Entrez votre e-mail" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3" name="reset"><i class="fa fa-mail-forward"></i> Envoyer le lien de réinitialisation</button>
      </form>

      <div class="login-links">
        <a href="login.php">Je me souviens de mon mot de passe</a>
        <a href="index.php"><i class="fa fa-home"></i> Retour à l'accueil</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>