<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<link rel="stylesheet" href="dist/css/product.css">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-header">Nos Produits </h1>
                        <div class="row">
                            <?php
                            $conn = $pdo->open();
                            try {
                                $stmt = $conn->prepare("SELECT * FROM products ");
                                $stmt->execute();
                                foreach ($stmt as $row) {
                                    $image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
                                    echo "
                                        <div class='col-sm-4'>
                                            <div class='product-card mb-4'>
                                                <div class='product-image-container'>
                                                    <img src='".$image."' alt='".$row['name']."' class='product-image'>
                                                </div>
                                                <div class='product-details'>
                                                    <h2 class='product-title'>".$row['name']."</h2>
                                                    <p class='product-description'>".substr($row['description'], 0, 100)."...</p>
                                                    <div class='buttons-container'>
                                                        <a href='product.php?product=".$row['slug']."' class='cart-btn'>
                                                            <i class='fa fa-eye'></i> Voir details
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ";
                                }
                            } catch(PDOException $e) {
                                echo "There is some problem in connection: " . $e->getMessage();
                            }
                            $pdo->close();
                            ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
  
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
