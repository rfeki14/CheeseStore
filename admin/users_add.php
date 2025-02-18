<?php
include 'includes/session.php';

// Handle new user creation
if (isset($_POST['email']) && isset($_POST['password']) &&isset($_POST['firstname']) && isset($_POST['lastname'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    
    // Insert the new user into the database
    $query = "INSERT INTO users (firstname, lastname, email, password, contact) 
              VALUES (:firstname, :lastname, :email, :password, :contact)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':email' => $email,
        'contact' =>$contact,
        ':password' => password_hash($password, PASSWORD_BCRYPT)
    ]);
    
    // Get the new user ID
    $new_user_id = $conn->lastInsertId();

    // After user creation, we will now link the addresses (passed from add_address.php)
    if (isset($_POST['address_ids'])) {
        $address_ids = explode(',',$_POST['address_ids']);
          // This should be passed after address insertion
        foreach ($address_ids as $address_id) {
            $query = "INSERT INTO user_addresses (user_id, address_id) 
                      VALUES (:user_id, :address_id)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':user_id' => $new_user_id,
                ':address_id' => $address_id
            ]);
        }
    }

    // Response to indicate success
    echo json_encode(['status' => 'success', 'user_id' => $new_user_id]);
    header('Location:'.'users.php');
    die();
}
?>
