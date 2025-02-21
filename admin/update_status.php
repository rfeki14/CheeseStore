<?php
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $status = $_POST['status'];

    $conn = $pdo->open();

    try{
        $stmt = $conn->prepare("UPDATE users SET status=:status WHERE id=:id");
        $stmt->execute(['status'=>$status, 'id'=>$id]);
        echo 'success';
    }
    catch(PDOException $e){
        echo 'error';
    }

    $pdo->close();
}
?>