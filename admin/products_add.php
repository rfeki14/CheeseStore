<?php
	include 'includes/session.php';
	include 'includes/slugify.php';
	require_once '../includes/ImageResize.php';
	use \Gumlet\ImageResize;

	if(isset($_POST['name'])){
		$name = $_POST['name'];
		$slug = slugify($name);
		$category = $_POST['category'];
		$description = $_POST['description'];
		$filename = $_FILES['photo']['name'];
		$qtty = $_POST["qtty"];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products WHERE slug=:slug");
		$stmt->execute(['slug'=>$slug]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Le produit existe déjà';
		}
		else{
			if(!empty($filename)){
				$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            	$dest='images/';
            	$new_filename='products/'.$slug.'.'.$ext;
				$image= new ImageResize($_FILES['photo']['tmp_name']);
				$image->resizeToBestFit(1000,1000);
				$image->save($new_filename);
			}
			else{
				$new_filename = '';
			}

			try{
				$stmt = $conn->prepare("INSERT INTO products (category_id, name, description, slug, qtty, photo) VALUES (:category, :name, :description, :slug, :qtty, :photo)");
				$stmt->execute(['category'=>$category, 'name'=>$name, 'description'=>$description, 'slug'=>$slug, 'qtty'=>$qtty, 'photo'=>$new_filename]);
				$_SESSION['success'] = 'Produit ajouté avec succès';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Veuillez d\'abord remplir le formulaire de produit';
	}

	header('location: products.php');
?>