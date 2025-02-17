<?php
include 'includes/session.php';
include 'includes/slugify.php';

$id = $_POST['id'];
$name = $_POST['name'];
$slug = slugify($name);
$category = $_POST['category'];
$description = $_POST['description'];
$qtty = $_POST['qtty'];

// Image upload handling
if(isset($_FILES['photo']) && $_FILES['photo']['size'] > 0){
    $target_dir = "../images/"; // Use the same path structure as in product listing
    $image_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate file type (only allow jpg, jpeg, png, gif)
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if(in_array($imageFileType, $allowed_types)){
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    } else {
        $_SESSION['error'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
        header('location: products.php');
        exit();
    }
}

$conn = $pdo->open();

try {
    // Fetch current product details to keep old image if new one is not uploaded
    $stmt = $conn->prepare("SELECT photo FROM products WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    // If new image is uploaded, update it; otherwise, keep the old one
    $photo = (!empty($image_name)) ? $image_name : $row['photo'];

    // Update product details in database
    $stmt = $conn->prepare("UPDATE products SET name=:name, slug=:slug, category_id=:category, qtty=:qtty, description=:description, photo=:photo WHERE id=:id");
    $stmt->execute([
        'name' => $name,
        'slug' => $slug,
        'category' => $category,
        'qtty' => $qtty,
        'description' => $description,
        'photo' => $photo, // Include updated or old image
        'id' => $id
    ]);

    $_SESSION['success'] = 'Product updated successfully';
} catch (PDOException $e) {
    $_SESSION['error'] = $e->getMessage();
}

$pdo->close();
header('location: products.php');
?>
