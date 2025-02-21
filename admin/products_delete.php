<?php
include 'includes/session.php';

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    
    try {
        $conn = $pdo->open();

        // D'abord, supprimer les détails de transaction associés
        $stmt = $conn->prepare("DELETE FROM details WHERE product_id=:id");
        $stmt->execute(['id'=>$id]);

        // Ensuite, supprimer le produit
        $stmt = $conn->prepare("DELETE FROM products WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Produit supprimé avec succès';
    }
    catch(PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else {
    $_SESSION['error'] = 'Sélectionnez d\'abord un produit à supprimer';
}

header('location: products.php');
?>