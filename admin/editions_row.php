<?php
include 'includes/session.php';
$conn = $pdo->open();

if(isset($_POST['id'])){
    $id = $_POST['id'];
    error_log("Received ID: " . $id); // Debugging Log

    $stmt = $conn->prepare("SELECT edition.*, products.name AS product_name FROM edition LEFT JOIN products ON products.id=edition.product_id WHERE edition.id=:id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch();


    echo json_encode([
        'editionid' => $row['id'],
        'editionname' => $row['name'],
        'product_id' => $row['product_id'], // Ensure correct product_id
        'product_name' => $row['product_name'], // Include product name
        'price' => $row['price'],
        'weight' => $row['weight'],
    ]);
} else {
    echo json_encode(['error' => 'ID not received']);
}

$pdo->close();
?>