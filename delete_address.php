<?php
include 'includes/session.php';

header('Content-Type: application/json');

if(!isset($_POST['address_id']) || !is_numeric($_POST['address_id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid address ID']);
    exit();
}

try {
    $conn = $pdo->open();
    $conn->beginTransaction();

    // Delete from user_addresses first (foreign key constraint)
    $stmt = $conn->prepare("DELETE FROM user_addresses WHERE address_id = :address_id AND user_id = :user_id");
    $stmt->execute(['address_id' => $_POST['address_id'], 'user_id' => $user['id']]);

    // Then delete from address table
    $stmt = $conn->prepare("DELETE FROM address WHERE id = :address_id");
    $stmt->execute(['address_id' => $_POST['address_id']]);

    $conn->commit();
    echo json_encode(['success' => true]);

} catch(PDOException $e) {
    if($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$pdo->close();
?>
