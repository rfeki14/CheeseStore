<?php
include 'includes/session.php';

$conn = $pdo->open();
$output = array('error'=>false);

if(isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['edition'])){
    $id = $_POST['id'];
    $quantity = intval($_POST['quantity']);
    $edition_id = $_POST['edition'];

    try {
        // Récupérer les informations du produit et de l'édition
        $stmt = $conn->prepare("
            SELECT e.*, p.name, p.photo, p.qtty as stock
            FROM edition e 
            LEFT JOIN products p ON p.id = e.product_id 
            WHERE e.id = :edition_id
        ");
        $stmt->execute(['edition_id' => $edition_id]);
        $product = $stmt->fetch();

        if($product){
            $price = $product['price'] * $quantity;

            if(isset($_SESSION['user'])){
                // Logique pour utilisateur connecté
                $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id=:user_id AND edition_id=:edition_id");
                $stmt->execute(['user_id' => $user['id'], 'edition_id' => $edition_id]);
                $row = $stmt->fetch();

                if($row){
                    $stmt = $conn->prepare("UPDATE cart SET quantity=:quantity, price=:price WHERE id=:id");
                    $stmt->execute(['quantity'=>$quantity, 'price'=>$price, 'id'=>$row['id']]);
                }
                else{
                    $stmt = $conn->prepare("INSERT INTO cart (user_id, edition_id, quantity, price) VALUES (:user_id, :edition_id, :quantity, :price)");
                    $stmt->execute(['user_id'=>$user['id'], 'edition_id'=>$edition_id, 'quantity'=>$quantity, 'price'=>$price]);
                }
            } 
            
            // Retourner les informations pour le stockage local
            foreach($_SESSION['cart'] as $cartitem){
                echo 'test'.$cartitem['name'].''.$cartitem['quantity']; 
                if($cartitem['edition_id'] == $edition_id){
                    $cartitem['quantity'] += $quantity;
                    $output['product'] = [
                        'cartid' => $cartitem['cartid'],
                        'edition_id' => $cartitem['edition_id'],
                        'product_id' => $cartitem['product_id'],
                        'name' => $cartitem['name'],
                        'photo' => $cartitem['photo'],
                        'quantity' => $cartitem['quantity'],
                        'priceu' => $cartitem['priceu'],
                        'price' => $cart_item['price'],
                        'weight' => $cartitem['weight'],
                        'stock' => $cartitem['stock']
                    ];
                }
            }
                $output['product'] = [
                    'cartid' => uniqid(), // ID unique pour le stockage local
                    'edition_id' => $edition_id,
                    'product_id' => $product['product_id'],
                    'name' => $product['name'],
                    'photo' => $product['photo'],
                    'quantity' => $quantity,
                    'priceu' => $product['price'],
                    'price' => $price,
                    'weight' => $product['weight'],
                    'stock' => $product['stock']
                ];
            $output['message'] = 'Produit ajouté au panier';
        }
        else{
            $output['error'] = true;
            $output['message'] = 'Edition invalide';
        }
    }
    catch(PDOException $e){
        $output['error'] = true;
        $output['message'] = $e->getMessage();
    }
}
else{
    $output['error'] = true;
    $output['message'] = 'Remplissez tous les champs requis';
}

$pdo->close();
echo json_encode($output);
?>