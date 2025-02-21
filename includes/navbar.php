<script>
  function getCartLength() {
    if (typeof(Storage) !== "undefined") {
      // Retrieve the existing cart or create a new one
      let cart = JSON.parse(localStorage.getItem('cart') || '[]');
      return cart.length;
    }
    return 0; // Return 0 if Storage is not supported
  }
</script>
<header class="main-header">
  <!-- Lien vers le CSS externe -->
  <link rel="stylesheet" href="dist/css/navbar.css">

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <!-- Logo -->
      <a href="index.php" class="navbar-brand">
        <img src="images/logo.png" width="50" height="50" alt="Coeur Blanc Logo">
      </a>

      <!-- Bouton de bascule de la barre de navigation mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>

      <!-- Éléments de la barre de navigation -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? '#about' : 'about.php'; ?>">À propos de nous</a>
          </li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contactez-nous</a></li>
          <li class="nav-item"><a class="nav-link" href="listeproduct.php">Produits</a></li>

          <!-- Menu déroulant des catégories -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown">
              Catégories
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
                echo "Il y a un problème de connexion : " . $e->getMessage();
              }
              $pdo->close();
              ?>
            </ul>
          </li>
        </ul>

        <!-- Barre de recherche -->
        <form method="POST" class="d-flex" action="search.php">
          <div class="input-group">
            <input type="text" class="form-control" name="keyword" placeholder="Rechercher..." required>
            <button class="btn btn-light" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </form>

        <!-- Section droite : Panier & Profil utilisateur -->
        <ul class="navbar-nav ms-3">
          <!-- Icône du panier -->
          <li class="nav-item position-relative">
            <a href="cart_view.php" class="nav-link">
              <i class="fa fa-shopping-cart fa-lg"></i>
              <span class="badge badge-light" style="color:#0d6efd" id="cart-count">
                <?php
                if(isset($_SESSION['user'])){
                    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id=:user_id");
                    $stmt->execute(['user_id'=>$user['id']]);
                    $row = $stmt->fetch();
                    echo $row['count'];
                }
                else{
                  echo "<script>document.write(getCartLength());</script>";
                }
                ?>
              </span>
            </a>
          </li>

          <!-- Menu déroulant du profil utilisateur -->
          <?php if(isset($_SESSION['user'])): ?>
            <?php $image = (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                <img src="<?= $image ?>" width="30" height="30"> <?= $user['firstname'] ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                <li><a class="dropdown-item" href="logout.php">Déconnexion</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
            <li class="nav-item"><a class="nav-link" href="signup.php">Inscription</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
</header>