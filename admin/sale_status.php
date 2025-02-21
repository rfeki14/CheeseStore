<?php
include 'includes/session.php';

if(isset($_POST['id']) && isset($_POST['status'])){
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("UPDATE sales SET status=:status WHERE id=:id");
        $stmt->execute(['status'=>$status, 'id'=>$id]);
        
        $_SESSION['success'] = 'Statut de la vente mis à jour avec succès';
        echo 'ok';
    }
    catch(PDOException $e){
        $_SESSION['error'] = $e->getMessage();
        echo 'error';
    }

    $pdo->close();
} else {
    echo 'error';
}
?>