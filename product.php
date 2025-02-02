<?php include 'includes/session.php'; ?>
<?php
    $conn = $pdo->open();
    $slug = $_GET['product'];

    try {
        $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid 
                                FROM products 
                                LEFT JOIN category ON category.id = products.category_id 
                                WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }

    // Page View Counter
    $now = date('Y-m-d');
    if ($product['date_view'] == $now) {
        $stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
        $stmt->execute(['id' => $product['prodid']]);
    } else {
        $stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
        $stmt->execute(['id' => $product['prodid'], 'now' => $now]);
    }
?>

<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
    <link rel="stylesheet" href="dist/css/product.css">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                        <div class="product-card">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" 
                                         class="product-image">
                                </div>
                                <div class="col-md-6">
                                    <h1 class="product-title"><?php echo $product['prodname']; ?></h1>
                                    <h3 class="product-price">&#36; <?php echo number_format($product['price'], 2); ?></h3>
                                    <p><b>Category:</b> 
                                        <a href="category.php?category=<?php echo $product['cat_slug']; ?>" class="category-link">
                                            <?php echo $product['catname']; ?>
                                        </a>
                                    </p>
                                    <p><b>Description:</b></p>
                                    <p class="product-description"><?php echo $product['description']; ?></p>

                                    <!-- Add to Cart -->
                                    <form class="form-inline" id="productForm">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <button type="button" id="minus" class="quantity-btn"><i class="fa fa-minus"></i></button>
                                                <input type="text" name="quantity" id="quantity" class="form-control text-center" 
                                                       style="width: 60px; font-size: 18px;" value="1">
                                                <button type="button" id="add" class="quantity-btn"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
                                            <button type="submit" class="cart-btn">
                                                <i class="fa fa-shopping-cart"></i> Add to Cart
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Facebook Comments -->
                        <div class="comments-container">
                            <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" 
                                 data-numposts="10" width="100%">
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
            </section>
        </div>
    </div>

    <?php $pdo->close(); ?>
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>

<script>
    $(function(){
        $('#add').click(function(e){
            e.preventDefault();
            var quantity = $('#quantity').val();
            quantity++;
            $('#quantity').val(quantity);
        });

        $('#minus').click(function(e){
            e.preventDefault();
            var quantity = $('#quantity').val();
            if(quantity > 1){
                quantity--;
            }
            $('#quantity').val(quantity);
        });
    });
</script>

</body>
</html>
