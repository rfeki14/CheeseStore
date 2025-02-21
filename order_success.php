<link rel="stylesheet" href="dist/css/cart_view.css">
<?php
include 'includes/session.php';
include 'includes/header.php';

if(!isset($_SESSION['user']) || !isset($_GET['id'])) {
    header('location: index.php');
    exit();
}

$sales_id = $_GET['id'];

try {
    $conn = $pdo->open();
    
    $stmt = $conn->prepare("
        SELECT s.* 
        FROM sales s
        WHERE s.id = :sales_id AND s.user_id = :user_id
    ");
    
    $stmt->execute(['sales_id' => $sales_id, 'user_id' => $_SESSION['user']]);
    $order = $stmt->fetch();
    
    if(!$order) {
        throw new Exception("Commande non trouvée");
    } else if($order['delivery_method'] == 'pickup') {
        $stmt = $conn->prepare("SELECT * FROM stores s, address a WHERE s.address = a.id AND s.id = :store_id");
        $stmt->execute(['store_id' => $order['dp_address']]);
        $store = $stmt->fetch();        

        if (!$store) {
            throw new Exception("Magasin non trouvé");
        }
    } else {
        $stmt = $conn->prepare("SELECT * FROM address WHERE id = :address_id");
        $stmt->execute(['address_id' => $order['dp_address']]);
        $address = $stmt->fetch();

        if (!$address) {
            throw new Exception("Adresse non trouvée");
        }
    }
    
    // Récupérer les détails de la commande
    $stmt = $conn->prepare("
        SELECT d.*, p.name, e.price
        FROM details d LEFT JOIN edition e ON d.product_id = e.id 
        JOIN products p ON e.product_id = p.id 
        WHERE d.sales_id = :sales_id
    ");
    $stmt->execute(['sales_id' => $sales_id]);
    $items = $stmt->fetchAll();
    
    $pdo->close();
} catch(Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('location: profile.php');
    exit();
}
?>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-solid">
                            <div class="box-body">
                                <div class="text-center">
                                    <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                                    <h2>Merci !</h2>
                                    <h4>Votre commande a été passée avec succès</h4>
                                    <p>Référence de la commande : #<?php echo $sales_id; ?></p>
                                    
                                    <div class="order-details mt-4">
                                        <h5>Détails de la commande :</h5>
                                        <p>Montant total : <?php echo number_format($order['total'], 2); ?> DT</p>
                                        <p>Méthode de livraison : <?php echo ucfirst($order['delivery_method']); ?></p>
                                        
                                        <?php if($order['delivery_method'] == 'delivery'): ?>
                                            <p>Adresse de livraison :<br>
                                            <?php echo $address['street'] . ', ' . 
                                                     $address['city'] . ', ' . 
                                                     $address['state'] . ' ' . 
                                                     $address['zip_code']; ?>
                                            </p>
                                        <?php else: ?>
                                            <p>Lieu de retrait : <?php echo  $store['name']; ?><br>
                                            <?php echo $store['street'] . ', ' . 
                                                     $store['city'] . ', ' . 
                                                     $store['state'] . ' ' . 
                                                     $store['zip_code']; ?>
                                            </p>
                                        <?php endif; ?>

                                        <h5 class="mt-4">Articles commandés :</h5>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Produit</th>
                                                        <th>Quantité</th>
                                                        <th>Prix</th>
                                                        <th>Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($items as $item): ?>
                                                    <tr>
                                                        <td><?php echo $item['name']; ?></td>
                                                        <td><?php echo $item['quantity']; ?></td>
                                                        <td><?php echo number_format(($item['price']), 2); ?> DT</td>
                                                        <td><?php echo number_format($item['quantity'] * $item['price'], 2); ?> DT</td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <a href="profile.php" class="btn btn-primary">Voir mes commandes</a>
                                        <a href="index.php" class="btn btn-default">Continuer mes achats</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php include 'includes/scripts.php'; ?>
</body>
</html>