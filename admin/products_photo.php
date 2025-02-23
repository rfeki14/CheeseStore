<?php
	include 'includes/session.php';
	require_once '../includes/ImageResize.php';
	use Gumlet\ImageResize;

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$filename = $_FILES['photo']['name'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT * FROM products WHERE id=:id");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();

		if(!empty($filename)){
			$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $dest='images/';
            $new_filename='products/'.$slug.'.'.$ext;
			$image= new ImageResize($_FILES['photo']['tmp_name']);
			$image->resizeToBestFit(1000,1000);
			$image->save($new_filename);	
		}
		
		try{
			$stmt = $conn->prepare("UPDATE products SET photo=:photo WHERE id=:id");
			$stmt->execute(['photo'=>$new_filename, 'id'=>$id]);
			$_SESSION['success'] = 'Photo du produit mise à jour avec succès';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord un produit pour mettre à jour la photo';
	}

	header('location: products.php');
?>