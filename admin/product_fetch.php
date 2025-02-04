<?php
include 'includes/session.php';
$conn = $pdo->open();

try {
    $stmt = $conn->prepare("SELECT id,name FROM products ORDER BY name ASC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products); // Ensure valid JSON response
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$pdo->close();
?>
