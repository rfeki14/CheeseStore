<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
<link rel="stylesheet" href="dist/css/index.css">

<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <!-- Full-screen carousel -->
        <div class="container-fluid p-0">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="3"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="4"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="5"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="6"></button>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="7"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/banner1.jpg" class="d-block w-100" alt="First slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner2.jpg" class="d-block w-100" alt="Second slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner3.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner4.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner5.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner6.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner7.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner8.jpg" class="d-block w-100" alt="Third slide" style="height: 100vh; object-fit: cover;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <div class="container">
            <section class="content">
                <!-- Add Products Title -->
                <div class="row">
                    <div class="col-12 text-center mb-4">
                    <link rel="stylesheet" href="dist/css/about.css"> 
                        <h2 class="title">Our Products</h2>
                        <div class="section-line"></div>
                    </div>
                </div>

                <div id="productsCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $conn = $pdo->open();
                        try {
                            $stmt = $conn->prepare("SELECT * FROM products");
                            $stmt->execute();
                            $products = $stmt->fetchAll();
                            
                            // Group products in sets of 4 (single row)
                            $productGroups = array_chunk($products, 4);
                            
                            foreach ($productGroups as $index => $group) {
                                ?>
                                <div class="carousel-item <?= ($index === 0) ? 'active' : '' ?>">
                                    <div class="row">
                                        <?php
                                        foreach ($group as $product) {
                                            $image = (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg';
                                            ?>
                                            <div class="col-lg-3 col-md-6 mb-4">
                                                <div class="product-card">
                                                    <div class="product-image">
                                                        <img src="<?= $image ?>" alt="<?= $product['name'] ?>" class="img-fluid">
                                                        <!-- Product Actions Overlay -->
                                                        <div class="product-actions">
                                                            <a href="product.php?product=<?= $product['slug'] ?>" class="action-btn" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button class="action-btn addcart" data-id="<?= $product['id'] ?>" title="Add to Cart">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="product-info">
                                                        <h5><a href="product.php?product=<?= $product['slug'] ?>"><?= $product['name'] ?></a></h5>
                                                        <p class="price" style="color: #e63946;"><?= number_format($product['price'], 3) ?> DT</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        catch(PDOException $e) {
                            echo "There is some problem in connection: " . $e->getMessage();
                        }
                        $pdo->close();
                        ?>
                    </div>
                    
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#productsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
            </section>
        </div>
    </div>

    <div id="about">
        <?php include 'includes/about.php'; ?>
    </div>

    <!-- Full-Width Google Map Section -->
    <div class="map-section">
        <h2 class="map-title">📍 Visit Us in Tunis</h2>
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3318.5789869140363!2d10.181502615288342!3d36.80197457995357!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd337a5c1157b5%3A0x4d5b62449935ff63!2sAvenue%20Habib%20Bourguiba%2C%20Tunis!5e0!3m2!1sen!2stn!4v1714023456789" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
   <!-- Icons & Information Section (Placed Below Map) -->
   <div class="info-section">
        <div class="info-container">
            <div class="info-box">
                <i class="fas fa-shipping-fast"></i>
                <p>Zones de livraison</p>
            </div>
            <div class="info-box">
                <i class="fas fa-clock"></i>
                <p>Mode et délai de livraison</p>
            </div>
            <div class="info-box">
                <i class="fas fa-hand-holding-usd"></i>
                <p>Modalité de paiement</p>
            </div>
            <div class="info-box">
                <i class="fas fa-headset"></i>
                <p>Service clients</p>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
