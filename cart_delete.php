<?php
	include 'includes/session.php';

	$conn = $pdo->open();

	$output = array('error'=>false);
	if(isset($_POST['id'])){
		$id = $_POST['id'];
	} else {
		$output['error'] = true;
		$output['message'] = 'ID not set';
		echo json_encode($output);
		exit();
	}

	if(isset($_SESSION['user'])){
		try{
			$stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
			$stmt->execute(['id'=>$id]);
			$output['message'] = 'Deleted';
			
		}
		catch(PDOException $e){
			$output['message'] = $e->getMessage();
		}
	}
	else{
		$productFound = false;
		foreach($_SESSION['cart'] as $key => $row){
			if($row['productid'] == $id){
				unset($_SESSION['cart'][$key]);
				$output['message'] = 'Deleted';
				$productFound = true;
				break;
			}
		}
		if (!$productFound) {
			$output['message'] = 'Product not found in cart';
		}
	}

	$pdo->close();
	echo json_encode($output);

?>