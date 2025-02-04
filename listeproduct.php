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
                        <h1 class="page-header">Our Products</h1>
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
                                                    <div class='quantity-control'>
                                                        <div class='quantity-buttons-left'>
                                                            <button type='button' class='quantity-btn' data-value='-500'>-500g</button>
                                                            <button type='button' class='quantity-btn' data-value='-100'>-100g</button>
                                                            <button type='button' class='quantity-btn' data-value='-50'>-50g</button>
                                                        </div>
                                                        <input type='number' name='quantity' class='quantity-input' 
                                                               value='100' min='50' max='5000' step='50' 
                                                               data-base-price='".$row['price']."'>
                                                        <div class='quantity-buttons-right'>
                                                            <button type='button' class='quantity-btn' data-value='50'>+50g</button>
                                                            <button type='button' class='quantity-btn' data-value='100'>+100g</button>
                                                            <button type='button' class='quantity-btn' data-value='500'>+500g</button>
                                                        </div>
                                                    </div>
                                                    <div class='price-display'>
                                                        <span>Prix: </span>
                                                        <span class='calculated-price'>".number_format(($row['price'] * 0.1), 3)."</span>
                                                        <span> DT</span>
                                                    </div>
                                                    <button class='cart-btn addcart' data-id='".$row['id']."'>
                                                        <i class='fa fa-shopping-cart'></i> Add to Cart
                                                    </button>
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
