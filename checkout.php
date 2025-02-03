<?php
session_start();
include 'includes/session.php';

// Fetch user profile data from the database
$userProfile = [
    'billing_address' => '',
    'delivery_address' => ''
];
$_POST['total'] = $_POST['total'] ?? 0;
$userProfile['billing_address'] = $userProfile['billing_address'] ?? '';
$userProfile['delivery_address'] = $userProfile['delivery_address'] ?? '';

if (isset($_SESSION['user'])) {
    $conn = $pdo->open();
    try {
        // Fetch user addresses
        $stmt = $conn->prepare("
            SELECT 
                b.street AS billing_street, b.city AS billing_city, b.state AS billing_state, b.zip_code AS billing_zip, b.country AS billing_country,
                d.street AS delivery_street, d.city AS delivery_city, d.state AS delivery_state, d.zip_code AS delivery_zip, d.country AS delivery_country
            FROM users u
            LEFT JOIN address b ON u.billing_address_id = b.id
            LEFT JOIN address d ON u.delivery_address_id = d.id
            WHERE u.id = :id
        ");
        $stmt->execute(['id' => $_SESSION['user']]);
        $userProfile = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "There was a problem fetching user data: " . $e->getMessage();
    }
    $pdo->close();
}

function displayAddressForm($type, $address) {
    echo "<h3>{$type} Address</h3>";
    echo '<label for="'.$type.'_street">Street:</label>';
    echo '<input type="text" id="'.$type.'_street" name="'.$type.'_street" class="form-control" value="'.htmlspecialchars($address[$type.'_street'] ?? '').'" required><br>';
    echo '<label for="'.$type.'_city">City:</label>';
    echo '<input type="text" id="'.$type.'_city" name="'.$type.'_city" class="form-control" value="'.htmlspecialchars($address[$type.'_city'] ?? '').'" required><br>';
    echo '<label for="'.$type.'_state">State:</label>';
    echo '<input type="text" id="'.$type.'_state" name="'.$type.'_state" class="form-control" value="'.htmlspecialchars($address[$type.'_state'] ?? '').'" required><br>';
    echo '<label for="'.$type.'_zip">Zip Code:</label>';
    echo '<input type="text" id="'.$type.'_zip" name="'.$type.'_zip" class="form-control" value="'.htmlspecialchars($address[$type.'_zip'] ?? '').'" required><br>';
    echo '<label for="'.$type.'_country">Country:</label>';
    echo '<input type="text" id="'.$type.'_country" name="'.$type.'_country" class="form-control" value="'.htmlspecialchars($address[$type.'_country'] ?? '').'" required><br>';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $deliveryMethod = $_POST['delivery_method'];
    $billingAddress = [
        'street' => $_POST['billing_street'] ?? '',
        'city' => $_POST['billing_city'] ?? '',
        'state' => $_POST['billing_state'] ?? '',
        'zip' => $_POST['billing_zip'] ?? '',
        'country' => $_POST['billing_country'] ?? ''
    ];
    $deliveryAddress = [
        'street' => $_POST['delivery_street'] ?? '',
        'city' => $_POST['delivery_city'] ?? '',
        'state' => $_POST['delivery_state'] ?? '',
        'zip' => $_POST['delivery_zip'] ?? '',
        'country' => $_POST['delivery_country'] ?? ''
    ];
    $pickupLocation = $_POST['pickup_location'] ?? '';
    $totalAmount = $_POST['total'] ?? 0;

    // Save the addresses and total amount to the session or database
    $_SESSION['billing_address'] = $billingAddress;
    $_SESSION['delivery_address'] = $deliveryAddress;
    $_SESSION['pickup_location'] = $pickupLocation;
    $_SESSION['total_amount'] = $totalAmount;

    // Redirect to the next step or confirmation page
    header('Location: confirmation.php');
    exit;
}
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="dist/css/cart_view.css">

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
     
    <div class="content-wrapper">
        <div class="container">

            <!-- Main content -->
            <section class="content">
                <h2>Checkout</h2>
                <div class="box box-solid">
                    <div class="box-body">
                        <form method="POST" action="checkout.php">
                            <input type="hidden" name="total" value="<?php echo htmlspecialchars($_POST['total'] ?? 0); ?>">
                            <div class="form-group">
                                <label for="delivery_method">Delivery Method:</label>
                                <select id="delivery_method" name="delivery_method" class="form-control" required>
                                    <option value="delivery">Delivery</option>
                                    <option value="pickup">In-Store Pickup</option>
                                </select>
                            </div>

                            <div id="pickup_location_section" style="display: none;">
                                <h3>Pickup Location</h3>
                                <label>Choose a location:</label><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pickup_location" id="location1" value="location1" required>
                                    <label class="form-check-label" for="location1">
                                        Location 1
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="pickup_location" id="location2" value="location2" required>
                                    <label class="form-check-label" for="location2">
                                        Location 2
                                    </label>
                                </div>
                            </div>

                            <div id="billing_address_section">
                                <?php displayAddressForm('billing', $userProfile); ?>
                            </div>

                            <div id="delivery_address_section" style="display: none;">
                                <?php displayAddressForm('delivery', $userProfile); ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Proceed to Confirmation</button>
                        </form>
                    </div>
                </div>
            </section>
         
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
document.getElementById('delivery_method').addEventListener('change', function() {
    var method = this.value;
    var pickupSection = document.getElementById('pickup_location_section');
    var deliverySection = document.getElementById('delivery_address_section');
    var billingSection = document.getElementById('billing_address_section');

    if (method === 'pickup') {
        pickupSection.style.display = 'block';
        deliverySection.style.display = 'none';
        billingSection.style.display = 'block';
    } else {
        pickupSection.style.display = 'none';
        deliverySection.style.display = 'block';
        billingSection.style.display = 'block';
    }
});
</script>
</body>
</html>