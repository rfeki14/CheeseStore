<?php
include 'includes/session.php';

if (isset($_GET['pay'])) {
    $payid = intval($_GET['pay']); // Sécurisation de l'input
    $date = date('Y-m-d');

    $conn = $pdo->open();
    
    try {
        // Vérification si l'adresse existe
        $dp_address = $_POST['dp_address']; // Assure-toi que l'adresse est envoyée par le formulaire
        $stmt_check_address = $conn->prepare("SELECT COUNT(*) FROM address WHERE id = :dp_address");
        $stmt_check_address->execute(['dp_address' => $dp_address]);
        $address_exists = $stmt_check_address->fetchColumn();

        if (!$address_exists) {
            throw new Exception('L\'adresse fournie n\'existe pas.');
        }

        // Activation des transactions pour assurer l'intégrité des données
        $conn->beginTransaction();
        
        // Insérer la vente
        $stmt_sales = $conn->prepare("INSERT INTO sales (user_id, pay_id, sales_date, dp_address) VALUES (:user_id, :pay_id, :sales_date, :dp_address)");
        $stmt_sales->execute(['user_id' => $user['id'], 'pay_id' => $payid, 'sales_date' => $date, 'dp_address' => $dp_address]);
        $salesid = $conn->lastInsertId();

        // Récupérer les produits du panier
        $stmt_cart = $conn->prepare("SELECT product_id, quantity FROM cart WHERE user_id = :user_id");
        $stmt_cart->execute(['user_id' => $user['id']]);
        $cart_items = $stmt_cart->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($cart_items)) {
            // Insérer les détails de vente
            $stmt_details = $conn->prepare("INSERT INTO details (sales_id, product_id, quantity) VALUES (:sales_id, :product_id, :quantity)");
            foreach ($cart_items as $row) {
                $stmt_details->execute(['sales_id' => $salesid, 'product_id' => $row['product_id'], 'quantity' => $row['quantity']]);
            }

            // Supprimer les articles du panier après insertion réussie
            $stmt_delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
            $stmt_delete_cart->execute(['user_id' => $user['id']]);

            $_SESSION['success'] = 'Transaction successful. Thank you.';
        } else {
            $_SESSION['error'] = 'Your cart is empty.';
        }

        // Valider la transaction
        $conn->commit();
    } catch (PDOException $e) {
        $conn->rollBack(); // Annuler la transaction en cas d'erreur
        $_SESSION['error'] = "Transaction failed: " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}

header('location: profile.php');
exit();
?>
