<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }

  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }
?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="dist/css/signup.css">

<body class="hold-transition register-page">
  <div class="overlay"></div>

  <div class="register-box">
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

    <div class="register-box-body">
      <h2 class="text-primary mb-3">Créez votre compte</h2>

      <form action="register.php" method="POST">
        <div class="form-group">
          <input type="text" class="form-control" name="firstname" placeholder="Prénom" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" name="lastname" placeholder="Nom de famille" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>" required>
        </div>

        <div class="form-group">
          <input type="email" class="form-control" name="email" placeholder="Adresse e-mail" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" name="repassword" placeholder="Confirmer le mot de passe" required>
        </div>

        <?php
          if(!isset($_SESSION['captcha'])){
            echo '<div class="form-group"><div class="g-recaptcha" data-sitekey="6LcxXmIaAAAAAFv3FBdhdKAAZ3vILm5SgSZFH94P"></div></div>';
          }
        ?>

        <button type="submit" class="btn btn-primary mt-3" name="signup"><i class="fa fa-pencil"></i> S'inscrire</button>
      </form>

      <div class="register-links">
        <a href="login.php">Vous avez déjà un compte ? Connectez-vous</a>
        <a href="index.php"><i class="fa fa-home"></i> Retour à l'accueil</a>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>