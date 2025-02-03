<?php
include 'includes/session.php';

if(isset($_POST['delete'])){
    $id = $_POST['id'];
    
    try {
        $conn = $pdo->open();

        // First delete related transaction details
        $stmt = $conn->prepare("DELETE FROM transactiondetails WHERE productId=:id");
        $stmt->execute(['id'=>$id]);

        // Then delete the product
        $stmt = $conn->prepare("DELETE FROM products WHERE id=:id");
        $stmt->execute(['id'=>$id]);

        $_SESSION['success'] = 'Product deleted successfully';
    }
    catch(PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    $pdo->close();
}
else {
    $_SESSION['error'] = 'Select product to delete first';
}

header('location: products.php');
?>