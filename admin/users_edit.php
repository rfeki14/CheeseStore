<?php
include 'includes/session.php';
$conn = $pdo->open();

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $contact = $_POST['contact'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        $sql = "UPDATE users SET email = :email, firstname = :firstname, lastname = :lastname, contact_info = :contact";
        if ($password) {
            $sql .= ", password = :password";
        }
        $sql .= " WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':contact', $contact);
        if ($password) {
            $stmt->bindParam(':password', $password);
        }
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Gestion des adresses
        if (isset($_POST['addresses'])) {
            $existingIds = [];

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
                    $existingIds[] = $address['id'];
                } else {
                    // Insérer une nouvelle adresse
                    $stmt = $conn->prepare("INSERT INTO address (street, city, state, zip_code, country) VALUES (:street, :city, :state, :zip_code, :country)");
                    $stmt->execute([
                        'street' => $address['street'],
                        'city' => $address['city'],
                        'state' => $address['state'],
                        'zip_code' => $address['zip_code'],
                        'country' => $address['country']
                    ]);
                    $newAddressId = $conn->lastInsertId();

                    // Associer la nouvelle adresse à l'utilisateur
                    $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, address_id) VALUES (:user_id, :address_id)");
                    $stmt->execute([
                        'user_id' => $id,
                        'address_id' => $newAddressId
                    ]);
                    $existingIds[] = $newAddressId;
                }
            }

            // Supprimer les adresses supprimées dans le formulaire
            if (!empty($existingIds)) {
                $stmt = $conn->prepare("DELETE FROM address WHERE id NOT IN (" . implode(',', $existingIds) . ") AND id IN (SELECT address_id FROM user_addresses WHERE user_id = :id)");
                $stmt->execute(['id' => $id]);
            }
        }

        $_SESSION['success'] = "Utilisateur mis à jour avec succès.";
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

$pdo->close();
header('location: users.php');
?>
