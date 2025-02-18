<?php include 'includes/session.php'; ?>
<?php
    $slug = $_GET['category'];
    $conn = $pdo->open();

    try {
        // Select only categories that have products with qtty > 0
        $stmt = $conn->prepare("
            SELECT DISTINCT c.* 
            FROM category c 
            JOIN products p ON c.id = p.category_id 
            WHERE c.cat_slug = :slug 
            AND p.qtty > 0
        ");
        $stmt->execute(['slug' => $slug]);
        $cat = $stmt->fetch();

        // Check if category exists
        $catid = $cat ? $cat['id'] : null;
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }

    $pdo->close();
?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
<link rel="stylesheet" href="dist/css/category.css">


<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <h1 class="category-header">
                    <?php echo $cat ? htmlspecialchars($cat['name']) : 'Category Not Found'; ?>
                </h1>
                <div class="row">
                        <div class="product-grid">
                            <?php
                            $conn = $pdo->open();

                            try {
                                // Select only products with qtty > 0
                                $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid AND qtty > 0");
                                $stmt->execute(['catid' => $catid]);

                                // Check if there are products
                                if ($stmt->rowCount() > 0) {
                                    foreach ($stmt as $row) {
                                        $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
                                        echo "
                                            <div class='product-card'>
                                                <img src='" . htmlspecialchars($image) . "' class='product-image'>
                                                <h5>
                                                    <a href='product.php?product=" . htmlspecialchars($row['slug']) . "' 
                                                       class='product-title'>" . htmlspecialchars($row['name']) . "</a>
                                                </h5>
                                            </div>
                                        ";
                                    }
                                } else {
                                    echo "<p class='no-products'>No products available in this category.</p>";
                                }
                            } catch (PDOException $e) {
                                echo "<p class='text-danger'>There is some problem in connection: " . $e->getMessage() . "</p>";
                            }

                            $pdo->close();
                            ?>
                        </div>
                    </div>

                    <!-- Sidebar -->
                   
            </section>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
