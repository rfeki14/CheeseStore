<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		
		$conn = $pdo->open();

		$stmt = $conn->prepare("
			SELECT users.*, 
				   GROUP_CONCAT(
					   CONCAT(address.street, ', ', address.city, ', ', 
							 address.state, ' ', address.zip_code, ', ', address.country)
					   SEPARATOR '; '
				   ) as addresses
			FROM users 
			LEFT JOIN user_addresses ON users.id = user_addresses.user_id
			LEFT JOIN address ON user_addresses.address_id = address.id
			WHERE users.id = :id
			GROUP BY users.id
		");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();
		
		$pdo->close();

		echo json_encode($row);
	}
?>