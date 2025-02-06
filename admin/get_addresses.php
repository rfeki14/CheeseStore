<?php
include 'includes/session.php';

if(isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $conn = $pdo->open();

    try {
        $stmt = $conn->prepare("
            SELECT a.id, a.street, a.city, a.state, a.zip_code, a.country 
            FROM address a 
            INNER JOIN user_addresses ua ON a.id = ua.address_id 
            WHERE ua.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($addresses) {
            echo json_encode($addresses);
        } else {
            echo json_encode([]);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    $pdo->close();
} else {
    echo json_encode(['error' => 'User ID not provided']);
}
?>