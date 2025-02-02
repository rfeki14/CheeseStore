<div class="sidebar">
      <link rel="stylesheet" href="dist/css/sidebar.css">
    <!-- Most Viewed Today -->
    <div class="sidebar-card">
        <h5 class="sidebar-title text-primary">Most Viewed Today</h5>
        <ul class="list-group list-group-flush">
            <?php
            $now = date('Y-m-d');
            $conn = $pdo->open();
            $stmt = $conn->prepare("SELECT * FROM products WHERE date_view=:now ORDER BY counter DESC LIMIT 10");
            $stmt->execute(['now' => $now]);

            foreach ($stmt as $row) {
                echo "<li class='list-group-item'><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></li>";
            }
            $pdo->close();
            ?>
        </ul>
    </div>

    <!-- Subscription Box -->
    <div class="sidebar-card">
        <h5 class="sidebar-title text-success">Stay Updated</h5>
        <p class="text-center">Subscribe to get the latest offers and product updates.</p>
        <form method="POST" action="">
            <div class="input-group">
                <input type="email" class="form-control" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-info"><i class="fa fa-envelope"></i></button>
            </div>
        </form>
    </div>

    <!-- Social Media Links -->
    <div class="sidebar-card text-center">
        <h5 class="sidebar-title text-dark">Follow Us</h5>
        <div class="social-container">
            <a href="#" class="social-btn btn-facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="social-btn btn-twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="social-btn btn-instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="social-btn btn-google"><i class="fab fa-google"></i></a>
            <a href="#" class="social-btn btn-linkedin"><i class="fab fa-linkedin-in"></i></a>
        </div>
    </div>
</div>
