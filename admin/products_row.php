<?php
include 'includes/session.php';
$conn = $pdo->open();

if(isset($_POST['id'])){
    $id = $_POST['id'];
    error_log("Received ID: " . $id); // Debugging Log

    $stmt = $conn->prepare("SELECT products.*, category.name AS category_name FROM products LEFT JOIN category ON category.id=products.category_id WHERE products.id=:id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch();

    // Check if the product has an image, if not, set a default
    $image = (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/noimage.jpg';

    // Return the product details as JSON
    echo json_encode([
        'prodid' => $row['id'],
        'prodname' => $row['name'],
        'category_id' => $row['category_id'], // Ensure correct category_id
        'category_name' => $row['category_name'], // Include category name
        'price' => $row['price'],
        'qtty' => $row['qtty'],
        'photo' => $image, // Full image path
        'old_photo' => $row['photo'], // Old image filename (for reference)
        'description' => $row['description'],
    ]);
} else {
    echo json_encode(['error' => 'ID not received']);
}

$pdo->close();
?>
