<?php
include 'includes/session.php';

header('Content-Type: application/json');

if(isset($_POST['street']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip_code'])) {
    $user_id = $_SESSION['user'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $country = 'Tunisia'; // Assuming the country is Tunisia

    $conn = $pdo->open();

    try {
        // Insert the new address into the address table
        $stmt = $conn->prepare("INSERT INTO address (street, city, state, zip_code, country) VALUES (:street, :city, :state, :zip_code, :country)");
        $stmt->execute(['street'=>$street, 'city'=>$city, 'state'=>$state, 'zip_code'=>$zip_code, 'country'=>$country]);
        $address_id = $conn->lastInsertId();

        // Link the new address to the user in the user_addresses table
        $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)");
        $stmt->execute(['user_id'=>$user_id, 'address_id'=>$address_id]);

        echo json_encode(['success' => true, 'address_id' => $address_id]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    $pdo->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>