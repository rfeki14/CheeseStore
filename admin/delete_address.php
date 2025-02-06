<?php
include 'includes/session.php';

if(isset($_POST['id'])) {
    $address_id = $_POST['id'];

    $conn = $pdo->open();

    try {
        // Delete the address from the user_addresses table
        $stmt = $conn->prepare("DELETE FROM user_addresses WHERE address_id = :address_id");
        $stmt->execute(['address_id' => $address_id]);

        // Delete the address from the address table
        $stmt = $conn->prepare("DELETE FROM address WHERE id = :address_id");
        $stmt->execute(['address_id' => $address_id]);

        echo 'success';
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    $pdo->close();
} else {
    echo 'Invalid request';
}
?>