<?php
include 'includes/session.php';
include 'includes/slugify.php';

$id = $_POST['id'];
$name = $_POST['name'];
$slug = slugify($name);
$category = $_POST['category'];
$description = $_POST['description'];
$qtty = $_POST['qtty'];

// Gestion de l'upload d'image
if(isset($_FILES['photo']) && $_FILES['photo']['size'] > 0){
    $target_dir = "../images/"; // Utiliser la même structure de chemin que dans la liste des produits
    $image_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validation du type de fichier (n'autoriser que jpg, jpeg, png, gif)
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if(in_array($imageFileType, $allowed_types)){
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    } else {
        $_SESSION['error'] = "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        header('location: products.php');
        exit();
    }
}

$conn = $pdo->open();

try {
    // Récupérer les détails du produit actuel pour conserver l'ancienne image si aucune nouvelle n'est téléchargée
    $stmt = $conn->prepare("SELECT photo FROM products WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    // Si une nouvelle image est téléchargée, la mettre à jour ; sinon, garder l'ancienne
    $photo = (!empty($image_name)) ? $image_name : $row['photo'];

    // Mettre à jour les détails du produit dans la base de données
    $stmt = $conn->prepare("UPDATE products SET name=:name, slug=:slug, category_id=:category, qtty=:qtty, description=:description, photo=:photo WHERE id=:id");
    $stmt->execute([
        'name' => $name,
        'slug' => $slug,
        'category' => $category,
        'qtty' => $qtty,
        'description' => $description,
        'photo' => $photo, // Inclure l'image mise à jour ou l'ancienne
        'id' => $id
    ]);

    $_SESSION['success'] = 'Produit mis à jour avec succès';
} catch (PDOException $e) {
    $_SESSION['error'] = $e->getMessage();
}

$pdo->close();
header('location: products.php');
?>