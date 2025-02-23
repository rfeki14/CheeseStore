<?php
include 'includes/session.php';
include 'includes/slugify.php';
function compressImage($source, $destination, $quality) { 
    // Get image info 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
     
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
    } 
     
    // Save image 
    imagejpeg($image, $destination, $quality); 
     
    // Return compressed image 
    return $destination; 
}

function convert_filesize($bytes, $decimals = 2) { 
    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB'); 
    $factor = floor((strlen($bytes) - 1) / 3); 
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor]; 
}

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
    $fileName = 'products/'.$name.'.'.$imageFileType;
    $imageUploadPath = '../images/' . $fileName; 
    $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION); 
     
    // Allow certain file formats 
    $allowTypes = array('jpg','png','jpeg','gif'); 
    if(in_array($fileType, $allowTypes)){ 
        // Image temp source and size 
        $imageTemp = $_FILES["photo"]["tmp_name"]; 
        $imageSize = convert_filesize($_FILES["photo"]["size"]); 
         
        // Compress size and upload image 
        $compressedImage = compressImage($imageTemp, $imageUploadPath, 75); 
         
        if($compressedImage){ 
            $compressedImageSize = filesize($compressedImage); 
            $compressedImageSize = convert_filesize($compressedImageSize); 
             
            $status = 'success'; 
            $statusMsg = "Image compressed successfully."; 
        }else{ 
            $_SESSION['error'] = "Image compress failed!"; 
        } 
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