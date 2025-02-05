<?php
include 'includes/session.php';

$conn = $pdo->open();
$output = array('error'=>false);

if(!isset($_POST['id']) || !isset($_POST['quantity']) || !isset($_SESSION['user'])) {
    $output['error'] = true;
    $output['message'] = 'Session invalide ou données manquantes';
    echo json_encode($output);
    exit();
}

$id = intval($_POST['id']);
$quantity = intval($_POST['quantity']);
$user_id = $_SESSION['user'];

try {
    // Vérification et mise à jour en une seule transaction
    $conn->beginTransaction();

    // Vérifier l'article et le stock
    $stmt = $conn->prepare("
        SELECT c.id, e.prix, p.qtty as stock 
        FROM cart c 
        LEFT JOIN edition e ON e.id = c.edition_id
        LEFT JOIN products p ON p.id = e.product_id
        WHERE c.id = :id AND c.user_id = :user_id
        FOR UPDATE
    ");
    
    $stmt->execute([
        'id' => $id,
        'user_id' => $user_id
    ]);
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$row) {
        throw new Exception('Article non trouvé');
    }

    if($quantity > $row['stock']) {
        throw new Exception('Stock insuffisant');
    }

    // Mise à jour du panier
    $updateStmt = $conn->prepare("
        UPDATE cart 
        SET quantity = :qty,
            price = :price
        WHERE id = :id 
        AND user_id = :user_id
    ");
    
    $newPrice = $row['prix'] * $quantity;
    $success = $updateStmt->execute([
        'qty' => $quantity,
        'price' => $newPrice,
        'id' => $id,
        'user_id' => $user_id
    ]);

    if(!$success) {
        throw new Exception('Erreur de mise à jour');
    }

    $conn->commit();
    $output['success'] = true;
    $output['message'] = 'Mise à jour réussie';
    $output['newPrice'] = $newPrice;

} catch(Exception $e) {
    $conn->rollBack();
    $output['error'] = true;
    $output['message'] = $e->getMessage();
    error_log("Cart Update Error: " . $e->getMessage());
}

$pdo->close();
echo json_encode($output);

?>