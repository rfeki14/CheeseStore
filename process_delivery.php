<?php
include 'includes/session.php';
include 'includes/constants.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug des données reçues
error_log('POST data: ' . print_r($_POST, true));
error_log('SESSION data: ' . print_r($_SESSION, true));

if(!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'User session not found';
    header('location: login.php');
    exit();
}

if(!isset($_POST['delivery_method']) || !isset($_POST['total'])) {
    $_SESSION['error'] = 'Missing required data';
    error_log('Missing POST data: delivery_method or total');
    header('location: delivery_method.php');
    exit();
}

try {
    $conn = $pdo->open();
    $conn->beginTransaction();

    // Préparer les données
    $user_id = $_SESSION['user'];
    $total = floatval($_POST['total']);
    $delivery_method = $_POST['delivery_method'];
    
    // Debug des données avant insertion
    error_log("Attempting insert with: user_id=$user_id, total=$total, method=$delivery_method");
    
    // Vérifier les données de livraison
    if($delivery_method === 'delivery' && !isset($_POST['address_id'])) {
        throw new Exception('Delivery address not selected');
    } elseif($delivery_method === 'pickup' && !isset($_POST['store_location'])) {
        throw new Exception('Store location not selected');
    }

    // Ajouter les frais de livraison
    if($delivery_method === 'delivery') {
        $address = $_POST['address_id'];

        // Vérifier si l'adresse existe dans la table 'address'
        $stmt = $conn->prepare("SELECT COUNT(*) FROM user_addresses WHERE address_id = :address_id AND user_id = :user_id");
        $stmt->execute(['address_id' => $address, 'user_id' => $user_id]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception('Invalid delivery address');
        }

        $total += 7.00; // Ajouter les frais de livraison

    } else {
        $address = $_POST['store_location'];
    }

    // Insérer la vente
    $stmt = $conn->prepare("
        INSERT INTO sales (
            user_id, 
            total, 
            delivery_method, 
            dp_address,
            sales_date,
            status
        ) VALUES (
            :user_id, 
            :total, 
            :delivery_method, 
            :address, 
            NOW(),
            0
        )
    ");
    
    $result = $stmt->execute([
        'user_id' => $user_id,
        'total' => $total,
        'delivery_method' => $delivery_method,
        'address' => $address
    ]);

    if(!$result) {
        throw new Exception('Failed to insert sale: ' . implode(', ', $stmt->errorInfo()));
    }
    
    $sales_id = $conn->lastInsertId();
    error_log("Created sale with ID: $sales_id");

    // Récupérer les articles du panier avec les informations du produit
    $stmt = $conn->prepare("SELECT *, cart.quantity as cart_quantity, cart.price as cart_price 
                           FROM cart LEFT JOIN Edition ON Edition.id=cart.edition_id
                           LEFT JOIN products ON products.id=edition.product_id 
                           WHERE user_id=:user_id");
    $stmt->execute(['user_id'=>$user_id]);

    foreach($stmt as $row){
        // Insérer dans details
        $stmt2 = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
        $stmt2->execute([
            'sales_id'=>$sales_id, 
            'product_id'=>$row['edition_id'], 
            'quantity'=>$row['cart_quantity']
        ]);

        // Mettre à jour le stock
        $stmt3 = $conn->prepare("UPDATE products SET qtty = qtty - :quantity WHERE id=:product_id");
        $stmt3->execute([
            'quantity'=>$row['cart_quantity'],
            'product_id'=>$row['edition_id']
        ]);
    }

    // Vider le panier
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);

    $conn->commit();
    error_log("Transaction completed successfully");
    
    $_SESSION['success'] = 'Order placed successfully!';
    header('location: order_success.php?id=' . $sales_id);
    
} catch(Exception $e) {
    error_log("Error in process_delivery: " . $e->getMessage());
    if(isset($conn)) {
        $conn->rollBack();
    }
    $_SESSION['error'] = $e->getMessage();
    header('location: delivery_method.php');
} finally {
    if(isset($conn)) {
        $pdo->close();
    }
}
?>
