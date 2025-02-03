<?php
include 'includes/session.php';

if(isset($_POST['firstname'])) {  // Vérifie si le formulaire a été soumis
    $conn = $pdo->open();
    
    try {
        // Mise à jour des informations de base
        $stmt = $conn->prepare("UPDATE users SET 
            firstname = :firstname,
            lastname = :lastname,
            email = :email,
            contact_info = :contact,
            address = :address
            WHERE id = :id");
            
        $stmt->execute([
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'email' => $_POST['email'],
            'contact' => $_POST['contact'],
            'address' => $_POST['address'],
            'id' => $user['id']
        ]);

        // Traitement de la photo si présente
        if(!empty($_FILES['photo']['name'])){
            move_uploaded_file($_FILES['photo']['tmp_name'], 'images/'.$_FILES['photo']['name']);
            $stmt = $conn->prepare("UPDATE users SET photo = :photo WHERE id = :id");
            $stmt->execute(['photo' => $_FILES['photo']['name'], 'id' => $user['id']]);
        }

        $_SESSION['success'] = 'Profile updated successfully';
        echo 'success';
    }
    catch(PDOException $e) {
        $_SESSION['error'] = $e->getMessage();
        echo $e->getMessage();
    }

    $pdo->close();
} else {
    echo 'No data received';
}
?>