<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
        $productid = $_POST['productid'];
		$price = $_POST['price'];
		$weight=$_POST['weight'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products WHERE name=:name");
		$stmt->execute(['name'=>$name]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Product already exist';
		}
		else{
			try{
				$stmt=$conn->prepare("INSERT INTO edition (name, product_id, price, weight) VALUES (:name, :productid, :price, :weight)");
                $stmt->execute(['name'=>$name, 'productid'=>$productid, 'price'=>$price, 'weight'=>$weight]);
                $_SESSION['success'] = 'Product added successfully';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up product form first';
	}

	header('location: editions.php');

?>