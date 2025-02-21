<link rel="stylesheet" href="dist/css/cart_view.css">
<?php
include 'includes/session.php';

if(isset($_POST['firstname'])) {
    $conn = $pdo->open();
    
    try {
        // Verify password first
        if(!isset($_POST['current_password']) || empty($_POST['current_password'])) {
            echo 'Le mot de passe actuel est requis';
            exit();
        }

        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute(['id' => $user['id']]);
        $row = $stmt->fetch();

        if(!password_verify($_POST['current_password'], $row['password'])) {
            echo 'Mot de passe incorrect';
            exit();
        }

        $conn->beginTransaction();

        // Update password if requested
        if(isset($_POST['change_password']) && $_POST['change_password'] == 'on') {
            if(empty($_POST['new_password'])) {
                echo 'Nouveau mot de passe requis';
                exit();
            }
            
            $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute(['password' => $password, 'id' => $user['id']]);
        }

        // Mise à jour des informations de base de l'utilisateur
        $stmt = $conn->prepare("UPDATE users SET 
            firstname = :firstname,
            lastname = :lastname,
            email = :email,
            contact_info = :contact
            WHERE id = :id");
            
        $stmt->execute([
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'contact' => $_POST['contact'],
            'id' => $user['id']
        ]);

        // Gestion des adresses
        if(isset($_POST['addresses']) && is_array($_POST['addresses'])) {
            foreach($_POST['addresses'] as $key => $address) {
                // Debug output
                error_log("Processing address: " . print_r($address, true));
                
                // Ensure street and city are set and not empty after trimming
                if(!isset($address['street']) || !isset($address['city'])) {
                    error_log("Missing street or city keys in address data");
                    throw new Exception('Données d\'adresse incomplètes.');
                }

                $street = trim((string)$address['street']);
                $city = trim((string)$address['city']);

                if($street === '' || $city === '') {
                    error_log("Empty street or city after trimming");
                    throw new Exception('La rue et la ville ne peuvent pas être vides.');
                }

                // Set default values for optional fields
                $state = isset($address['state']) ? trim((string)$address['state']) : '';
                $zip_code = isset($address['zip_code']) ? trim((string)$address['zip_code']) : '';
                $country = isset($address['country']) ? trim((string)$address['country']) : 'France';

                try {
                    if(isset($address['id'])) {
                        // Update existing address
                        $stmt = $conn->prepare("UPDATE address SET street=:street, city=:city, state=:state, zip_code=:zip_code, country=:country WHERE id=:id");
                        $params = [
                            'street' => $street,
                            'city' => $city,
                            'state' => $state,
                            'zip_code' => $zip_code,
                            'country' => $country,
                            'id' => $address['id']
                        ];
                        error_log("Updating address with params: " . print_r($params, true));
                        $stmt->execute($params);
                    } else {
                        // Nouvelle adresse
                        $stmt = $conn->prepare("INSERT INTO address (street, city, state, zip_code, country) VALUES (:street, :city, :state, :zip_code, :country)");
                        $stmt->execute([
                            'street' => $street,
                            'city' => $city,
                            'state' => $state,
                            'zip_code' => $zip_code,
                            'country' => $country
                        ]);
                        
                        $address_id = $conn->lastInsertId();
                        
                        // Liaison avec l'utilisateur
                        $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)");
                        $stmt->execute([
                            'user_id' => $user['id'],
                            'address_id' => $address_id
                        ]);
                    }
                } catch(PDOException $e) {
                    error_log("Database error: " . $e->getMessage());
                    throw new Exception('Erreur lors de la sauvegarde de l\'adresse: ' . $e->getMessage());
                }
            }
        }

        // Traitement de la photo
        if(!empty($_FILES['photo']['name'])){
            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/'.$_FILES['photo']['name']);
            $stmt = $conn->prepare("UPDATE users SET photo = :photo WHERE id = :id");
            $stmt->execute(['photo' => $_FILES['photo']['name'], 'id' => $user['id']]);
        }

        $conn->commit();
        $_SESSION['success'] = 'Profil mis à jour avec succès';
        echo 'success';
    }
    catch(Exception $e) {
        $conn->rollBack();
        $_SESSION['error'] = $e->getMessage();
        echo $e->getMessage();
    }

    $pdo->close();
} else {
    echo 'Aucune donnée reçue';
}
?>