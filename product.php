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
                                            <div class="quantity-control">
                                                <div class="quantity-buttons-left">
                                                    <button type="button" class="quantity-btn" data-value="-1000">-1000g</button>
                                                    <button type="button" class="quantity-btn" data-value="-500">-500g</button>
                                                    <button type="button" class="quantity-btn" data-value="-100">-100g</button>
                                                </div>
                                                
                                                <input type="number" name="quantity" id="quantity" class="form-control text-center" 
                                                       value="1000" min="100" max="5000" step="100" 
                                                       data-base-price="<?php echo $product['price']; ?>">
                                                
                                                <div class="quantity-buttons-right">
                                                    <button type="button" class="quantity-btn" data-value="100">+100g</button>
                                                    <button type="button" class="quantity-btn" data-value="500">+500g</button>
                                                    <button type="button" class="quantity-btn" data-value="1000">+1000g</button>
                                                </div>
                                            </div>
                                            <div class="price-display">
                                                <span>Prix: </span>
                                                <span id="calculated-price"><?php echo number_format(($product['price'] * 0.1), 3); ?></span>
                                                <span> DT</span>
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
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const priceDisplay = document.getElementById('calculated-price');
        const basePrice = parseFloat(quantityInput.dataset.basePrice);

        function updatePrice() {
            const quantity = parseInt(quantityInput.value);
            // Convert to kg (divide by 1000) and multiply by base price
            const calculatedPrice = (quantity / 1000) * basePrice;
            priceDisplay.textContent = calculatedPrice.toFixed(3);
        }

        // Add event listeners to all quantity buttons
        document.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const value = parseInt(this.dataset.value);
                let currentQty = parseInt(quantityInput.value);
                currentQty += value;

                // Ensure quantity stays within bounds
                if (currentQty >= 50 && currentQty <= 5000) {
                    quantityInput.value = currentQty;
                    updatePrice();
                }
            });
        });

        // Update price when quantity is changed manually
        quantityInput.addEventListener('change', updatePrice);

        // Handle form submission
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const quantity = parseInt(quantityInput.value);
            const basePrice = parseFloat(quantityInput.dataset.basePrice);
            const calculatedPrice = (quantity / 1000) * basePrice;
            
            $.ajax({
                type: 'POST',
                url: 'cart_add.php',
                data: {
                    id: <?php echo $product['prodid']; ?>,
                    quantity: quantity,
                    price: calculatedPrice
                },
                dataType: 'json',
                success: function(response){
                    if(!response.error){
                        // Mise à jour du panier
                        getCart();
                        // Message de succès
                        alert('Product added to cart successfully');
                    }
                    else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while adding to cart');
                    console.error(error);
                }
            });
        });
    });
</script>

</body>
</html>
