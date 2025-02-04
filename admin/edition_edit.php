<?php
include 'includes/session.php';

$id = $_POST['id'];
$productid = $_POST['productid'];
$name = $_POST['name'];
$price = $_POST['price'];
$weight = $_POST['weight'];

$conn = $pdo->open();

try {
    $stmt=$conn->prepare("UPDATE edition SET name=:name, product_id=:productid, price=:price, weight=:weight WHERE id=:id");
    $stmt->execute(['name'=>$name, 'productid'=>$productid, 'price'=>$price, 'weight'=>$weight, 'id'=>$id]);
    $_SESSION['success'] = 'Product updated successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = $e->getMessage();
}

$pdo->close();
header('location: editions.php');
?>
