<?php include "includes/session.php"; ?>

<?php
if (isset($_SESSION["user"])) {
    $cartid = $_POST['id'];
    $total = $_POST['total'];

    try {
        $stmt = $conn->prepare("UPDATE cart SET total = :total WHERE id = :id AND user_id = :userid");
        $stmt->execute([
            ':total' => $total,
            ':id' => $cartid,
            ':userid' => $_SESSION["user"]
        ]);
        echo "ok";
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} elseif (isset($_SESSION["cart"])&&($_POST['total']!==0)) {
        $_SESSION['total']=$_POST['total'];
        echo 'ok';
} else {
    echo 'invalid request';
}
?>