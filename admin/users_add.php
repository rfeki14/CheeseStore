<?php
    include 'includes/session.php';

    if(isset($_POST['add'])){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];  // Nouvelles adresses de l'utilisateur
        $contact = $_POST['contact'];

        $conn = $pdo->open();

        // Vérification si l'email existe déjà
        $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users WHERE email=:email");
        $stmt->execute(['email'=>$email]);
        $row = $stmt->fetch();

        if($row['numrows'] > 0){
            $_SESSION['error'] = 'Email already taken';
        }
        else{
            $password = password_hash($password, PASSWORD_DEFAULT);
            $filename = $_FILES['photo']['name'];
            $now = date('Y-m-d');
            if(!empty($filename)){
                move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);    
            }

            try {
                // Insertion de l'utilisateur
                $stmt = $conn->prepare("INSERT INTO users (email, password, firstname, lastname, address, contact_info, photo, status, created_on) VALUES (:email, :password, :firstname, :lastname, :address, :contact, :photo, :status, :created_on)");
                $stmt->execute(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname, 'address'=>$address, 'contact'=>$contact, 'photo'=>$filename, 'status'=>1, 'created_on'=>$now]);
                $userId = $conn->lastInsertId(); // Récupérer l'ID de l'utilisateur ajouté
                
                // Gestion des adresses
                if (isset($_POST['addresses'])) {
                    foreach ($_POST['addresses'] as $address) {
                        if (!empty($address['id'])) {
                            // Mettre à jour l'adresse existante
                            $stmt = $conn->prepare("UPDATE address SET street = :street, city = :city, state = :state, zip_code = :zip_code, country = :country WHERE id = :id");
                            $stmt->execute([
                                'street' => $address['street'],
                                'city' => $address['city'],
                                'state' => $address['state'],
                                'zip_code' => $address['zip_code'],
                                'country' => $address['country'],
                                'id' => $address['id']
                            ]);
                        } else {
                            // Ajouter une nouvelle adresse
                            $stmt = $conn->prepare("INSERT INTO address (street, city, state, zip_code, country) VALUES (:street, :city, :state, :zip_code, :country)");
                            $stmt->execute([
                                'street' => $address['street'],
                                'city' => $address['city'],
                                'state' => $address['state'],
                                'zip_code' => $address['zip_code'],
                                'country' => $address['country']
                            ]);
                            $newAddressId = $conn->lastInsertId();

                            // Associer l'adresse à l'utilisateur
                            $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)");
                            $stmt->execute([
                                'user_id' => $userId,
                                'address_id' => $newAddressId
                            ]);
                        }
                    }
                }

                $_SESSION['success'] = 'User added successfully';
            }
            catch(PDOException $e){
                $_SESSION['error'] = $e->getMessage();
            }
        }

        $pdo->close();
    }
    else{
        $_SESSION['error'] = 'Fill up user form first';
    }

    header('location: users.php');
?>
