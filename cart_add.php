<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);

	$id = $_POST['id'];
	$quantity = $_POST['quantity'];
	$price = $_POST['price'];

	function getCartCount($conn, $userId) {
	    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id=:user_id");
	    $stmt->execute(['user_id' => $userId]);
	    $row = $stmt->fetch();
	    return $row['count'];
	}

	if(isset($_SESSION['user'])){
	    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id=:user_id AND product_id=:product_id");
	    $stmt->execute(['user_id'=>$user['id'], 'product_id'=>$id]);
	    $row = $stmt->fetch();
	    
	    // Récupérer le prix de base du produit
	    $stmt = $conn->prepare("SELECT price FROM products WHERE id=:id");
	    $stmt->execute(['id' => $id]);
	    $product = $stmt->fetch();
	    $basePrice = $product['price']; // Prix par kg
	    
	    if(!$row){
	        try{
	            $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price) VALUES (:user_id, :product_id, :quantity, :price)");
	            $stmt->execute([
	                'user_id' => $user['id'],
	                'product_id' => $id,
	                'quantity' => $quantity,
	                'price' => $basePrice // Prix par kg constant
	            ]);
	            $output['message'] = $quantity . 'g added to cart';
	        }
	        catch(PDOException $e){
	            $output['error'] = true;
	            $output['message'] = $e->getMessage();
	        }
	    }
	    else{
	        try {
	            $newQuantity = $row['quantity'] + $quantity;
	            if($newQuantity > 5000) { // Maximum 5kg
	                $output['error'] = true;
	                $output['message'] = 'Maximum quantity (5kg) exceeded';
	                echo json_encode($output);
	                exit();
	            }
	            if($newQuantity < 100) { // Minimum 100g
	                $output['error'] = true;
	                $output['message'] = 'Minimum quantity (100g) required';
	                echo json_encode($output);
	                exit();
	            }
	            
	            $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE user_id=:user_id AND product_id=:product_id");
	            $stmt->execute([
	                'user_id' => $user['id'],
	                'product_id' => $id,
	                'quantity' => $newQuantity
	            ]);
	            $output['message'] = $quantity . 'g added to cart (Total: ' . $newQuantity . 'g)';
	        }
	        catch(PDOException $e){
	            $output['error'] = true;
	            $output['message'] = $e->getMessage();
	        }
	    }
	    // Get updated cart count
	    $output['count'] = getCartCount($conn, $user['id']);
	}
	else{
	    // Pour les utilisateurs non connectés
	    // Récupérer le prix de base du produit
	    $stmt = $conn->prepare("SELECT price FROM products WHERE id=:id");
	    $stmt->execute(['id' => $id]);
	    $product = $stmt->fetch();
	    $basePrice = $product['price']; // Prix par kg

	    if(!isset($_SESSION['cart'])){
	        $_SESSION['cart'] = array();
	    }

	    if(isset($_SESSION['cart'][$id])){
	        $newQuantity = $_SESSION['cart'][$id]['quantity'] + $quantity;
	        if($newQuantity > 5000) {
	            $output['error'] = true;
	            $output['message'] = 'Maximum quantity (5kg) exceeded';
	        } else if($newQuantity < 100) {
	            $output['error'] = true;
	            $output['message'] = 'Minimum quantity (100g) required';
	        } else {
	            $_SESSION['cart'][$id]['quantity'] = $newQuantity;
	            $output['message'] = $quantity . 'g added to cart (Total: ' . $newQuantity . 'g)';
	        }
	    }
	    else{
	        $_SESSION['cart'][$id] = array(
	            'quantity' => $quantity,
	            'price' => $basePrice // Prix par kg constant
	        );
	        $output['message'] = $quantity . 'g added to cart';
	    }
	    $output['count'] = count($_SESSION['cart']);
	}

	$pdo->close();
	echo json_encode($output);

?>