<?php
include 'includes/session.php';

if (isset($_SESSION['user'])) {
    $userid = $_SESSION['user'];
    $billing_address = $_SESSION['billing_address'];
    $delivery_address = $_SESSION['delivery_address'];
    $pickup_location = $_SESSION['pickup_location'];
    $total_price = $_SESSION['total_amount'];
    $date = date('Y-m-d');

    $conn = $pdo->open();

    try {
        // Function to get or insert address and return its ID
        function getAddressId($conn, $address) {
            $stmt = $conn->prepare("SELECT id FROM address WHERE city=:city AND state=:state AND zip_code=:zip_code AND country=:country AND street=:street");
            $stmt->execute(['city' => $address['city'], 'state' => $address['state'], 'zip_code' => $address['zip'], 'country' => $address['country'], 'street' => $address['street']]);
            $row = $stmt->fetch();

            if ($row) {
                return $row['id'];
            } else {
                $stmt = $conn->prepare("INSERT INTO address (city, state, zip_code, country, street) VALUES (:city, :state, :zip_code, :country, :street)");
                $stmt->execute(['city' => $address['city'], 'state' => $address['state'], 'zip_code' => $address['zip'], 'country' => $address['country'], 'street' => $address['street']]);
                return $conn->lastInsertId();
            }
        }
		
		if (empty($delivery_address)) {
            $delivery_address = $pickup_location;
        }

        $billing_address_id = getAddressId($conn, $billing_address);
        $delivery_address_id = getAddressId($conn, $delivery_address);

        $stmt = $conn->prepare("INSERT INTO sales (user_id, total, sales_date, D_ad, B_ad) VALUES (:user_id, :total, :sales_date, :d_ad, :b_ad)");
        $stmt->execute([
            'user_id' => $userid,
            'total' => $total_price,
            'sales_date' => $date,
            'd_ad' => $delivery_address_id,
            'b_ad' => $billing_address_id
        ]);
        $salesid = $conn->lastInsertId();

        $stmt = $conn->prepare("SELECT * FROM cart LEFT JOIN products ON products.id=cart.product_id WHERE user_id=:user_id");
        $stmt->execute(['user_id' => $userid]);

        foreach ($stmt as $row) {
            $stmt = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
            $stmt->execute([
                'sales_id' => $salesid,
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity']
            ]);
            $stmt = $conn->prepare("UPDATE products SET qtty=qtty-:qb WHERE id=:product_id");
            $stmt->execute(['qb' => $row['quantity'], 'product_id' => $row['product_id']]);
        }

        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=:user_id");
        $stmt->execute(['user_id' => $userid]);

        $_SESSION['success'] = 'Transaction successful. Thank you.';
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Complete</title>
    <link rel="stylesheet" href="path/to/your/css/file.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $_SESSION['success']; ?></h2>
        <a href="index.php" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>
<?php
unset($_SESSION['success']);
header('location: index.php');
?>