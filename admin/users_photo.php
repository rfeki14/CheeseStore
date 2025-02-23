<?php
	include 'includes/session.php';
	require_once '../includes/ImageResize.php';
	use Gumlet\ImageResize;

	if(isset($_POST['upload'])){
		$id = $_POST['id'];
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			$ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $dest='images/';
            $filename='users/'.$user['id'].'.'.$ext;
            $image= new ImageResize($_FILES['photo']['tmp_name']);
            $image->resizeToBestFit(900, 900);
            $image->save($dest.$filename);
		}
		
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE users SET photo=:photo WHERE id=:id");
			$stmt->execute(['photo'=>$filename, 'id'=>$id]);
			$_SESSION['success'] = 'Photo de l\'utilisateur mise à jour avec succès';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();

	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord un utilisateur pour mettre à jour la photo';
	}

	header('location: users.php');
?>