<?php
include 'includes/session.php';

$output = array('error'=>false);

if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    if(isset($_SESSION['user'])){
        try{
            $conn = $pdo->open();
            // Vérifier d'abord si l'élément existe
            $stmt = $conn->prepare("SELECT * FROM cart WHERE id=:id");
            $stmt->execute(['id'=>$id]);
            
            if($stmt->rowCount() > 0){
                // Si l'élément existe, le supprimer
                $stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
                $stmt->execute(['id'=>$id]);
                $output['message'] = 'Item deleted successfully';
                
                // Retourner le nouveau nombre d'articles
                $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id=:user_id");
                $stmt->execute(['user_id'=>$user['id']]);
                $row = $stmt->fetch();
                $output['count'] = $row['count'];
            }
            else{
                $output['error'] = true;
                $output['message'] = 'Item not found in cart';
            }
            
            $pdo->close();
        }
        catch(PDOException $e){
            $output['error'] = true;
            $output['message'] = $e->getMessage();
        }
    }
    else{
        // Suppression du panier en session
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $key => $item){
                if($item['cartid'] == $id){
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Réindexer le tableau
                    $output['message'] = 'Item deleted successfully';
                    $output['count'] = count($_SESSION['cart']);
                    break;
                }
            }
        }
    }
}
else{
    $output['error'] = true;
    $output['message'] = 'No item ID provided';
}

echo json_encode($output);
?>