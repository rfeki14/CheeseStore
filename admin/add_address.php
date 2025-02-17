<?php
include "includes/session.php";

// Insert multiple addresses into the Address table
if (isset($_POST['phone']) && isset($_POST['street']) && isset($_POST['city']) && isset($_POST['state']) && isset($_POST['zip_code']) && isset($_POST['country'])) {
    $address=array(
        'id' =>0,
        'phone' => $_POST['phone'],
        'street'=> $_POST['street'],
        'city'=> $_POST['city'],
        'state' => $_POST['state'],
        'zip_code' => $_POST['zip_code'],
        'country'=> $_POST['country']
    );
        // Insert each address
        $query = "INSERT INTO address (phone, street, city, state, zip_code, country) 
                  VALUES (:phone, :street, :city, :state, :zip_code, :country)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':phone' => $address['phone'], 
            ':street' =>$address['street'],
            ':city' => $address['city'],
            ':state' => $address['state'],
            ':zip_code' => $address['zip_code'],
            ':country' => $address['country']
            ]);

        // Store the address ID
        $address['id'] = $conn->lastInsertId();

    if (isset($_POST['user_id']) && $_POST['user_id'] != '') {
        // Existing User Modal - link addresses to the user
        $user_id = $_POST['user_id'];
            $query = "INSERT INTO user_addresses (user_id, address_id) 
                      VALUES (:user_id, :address_id) 
                      ON DUPLICATE KEY UPDATE address_id = :address_id";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':user_id' => $user_id,
                ':address_id' => $address['id']
            ]);
        } else {
        // If it's a new user, send address IDs back to be linked later
        echo json_encode(['status' => 'success', 'address' => $address]);
    }
}else{
    echo json_encode(['status'=> 'error',''=> '']);
}
?>
