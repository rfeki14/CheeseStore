<?php
include 'includes/session.php';
include 'includes/slugify.php';
require_once '../includes/ImageResize.php';
use \Gumlet\ImageResize;

$id = $_POST['id'];
$name = $_POST['name'];
$slug = slugify($name);
$category = $_POST['category'];
$description = $_POST['description'];
$qtty = $_POST['qtty'];

// Gestion de l'upload d'image
if(!empty($_FILES["photo"]["name"])) { 
    // File info 
    $imageFileType = strtolower(pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION));
    $fileName = 'products/'.$slug.'.'.$imageFileType;
    $imageUploadPath = '../images/' . $fileName; 
    $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION); 
     
    // Allow certain file formats 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    if(in_array($fileType, $allowTypes)){ 
        // Image temp source and size 
        $imageTemp = $_FILES["photo"]["tmp_name"];   
        $image= new ImageResize($imageTemp);
        $image->resizeToBestFit(800, 800);
        $image->save($imageUploadPath);
    }else{ 
        $_SESSION['error'] = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
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
    $photo = (!empty($fileName)) ? $fileName : $row['photo'];

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