<?php
include 'includes/session.php';

if (!isset($_POST['id'], $_POST['status'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$id = intval($_POST['id']); // Ensure ID is an integer
$status = $_POST['status'] == 1 ? 1 : 0; // Ensure status is either 1 or 0

try {
    // Check if the user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    // Update user status
    $update = $conn->prepare("UPDATE sales SET status = ? WHERE id = ?");
    $updateSuccess = $update->execute([$status, $id]);

    if ($updateSuccess) {
        echo json_encode(['status' => 'ok', 'newStatus' => $status]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update status']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>