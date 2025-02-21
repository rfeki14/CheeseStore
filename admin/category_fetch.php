<?php
include 'includes/session.php';
$conn = $pdo->open();
if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    $conn = $pdo->open();

    $stmt = $conn->prepare("SELECT * FROM category WHERE id=:id");
    $stmt->execute(['id'=>$id]);
    $row = $stmt->fetch();
    
    $pdo->close();

    echo json_encode($row);
}else{
try {
    $stmt = $conn->prepare("SELECT * FROM category ORDER BY name ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($categories); // Ensure valid JSON response
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

    $pdo->close();
}
?>
