<?php
	include 'includes/session.php';
	require_once '../includes/ImageResize.php';
	use \Gumlet\ImageResize;

	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}
	else{
		$return = 'home.php';
	}

	if(isset($_POST['save'])){
		$curr_password = $_POST['curr_password'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$photo = $_FILES['photo']['name'];
		if(password_verify($curr_password, $admin['password'])){
			if(!empty($photo)){
				$image= new ImageResize($_FILES['photo']['tmp_name']);
				$image->resize(320, 320);
				$image->save('../images/users/0');
				$filename='users/0';
			}
			else{
				$filename = $admin['photo'];
			}

			if($password == $admin['password']){
				$password = $admin['password'];
			}
			else{
				$password = password_hash($password, PASSWORD_DEFAULT);
			}

			$conn = $pdo->open();

			try{
				$stmt = $conn->prepare("UPDATE users SET email=:email, password=:password, firstname=:firstname, lastname=:lastname, photo=:photo WHERE id=:id");
				$stmt->execute(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname, 'photo'=>$filename, 'id'=>$admin['id']]);

				$_SESSION['success'] = 'Compte mis à jour avec succès';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}

			$pdo->close();
		}
		else{
			$_SESSION['error'] = 'Mot de passe incorrect';
		}
	}
	else{
		$_SESSION['error'] = 'Veuillez d\'abord remplir les détails requis';
	}

	header('location:'.$return);
?>