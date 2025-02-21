<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

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
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$new_filename = $slug.'.'.$ext;
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);	
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