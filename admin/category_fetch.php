<?php
include 'includes/session.php';
$conn = $pdo->open();

try {
    $stmt = $conn->prepare("SELECT * FROM category ORDER BY name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categories); // Ensure valid JSON response
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$pdo->close();
?>
