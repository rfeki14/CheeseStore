<?php include 'includes/session.php'; ?>
<?php
    $conn = $pdo->open();
    $slug = $_GET['product'];

    try {
        // Récupération du produit avec ses éditions
        $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid 
                                FROM products 
                                LEFT JOIN category ON category.id = products.category_id 
                                WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        $product = $stmt->fetch();

        // Récupération des éditions du produit
        $stmt = $conn->prepare("SELECT * FROM edition WHERE product_id = :prodid order by weight");
        $stmt->execute(['prodid' => $product['prodid']]);
        $editions = $stmt->fetchAll();
    } catch (PDOException $e) {
        echo "Il y a un problème de connexion : " . $e->getMessage();
    }

    // Compteur de vues de la page
    $now = date('Y-m-d');
    if ($product['date_view'] == $now) {
        $stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
        $stmt->execute(['id' => $product['prodid']]);
    } else {
        $stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
        $stmt->execute(['id' => $product['prodid'], 'now' => $now]);
    }
?>

<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
    <link rel="stylesheet" href="dist/css/product.css">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="product-card">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" 
                                 class="product-image">
                        </div>
                        <div class="col-md-6">
                            <h1 class="product-title"><?php echo $product['prodname']; ?></h1>
                            
                            <!-- Sélecteur d'édition -->
                            <div class="form-group">
                                <label for="edition">Poids :</label>
                                <select name="edition" id="edition" class="form-control" required>
                                    <option value="">Sélectionnez un poids</option>
                                    <?php
                                    foreach($editions as $edition){
                                        echo "<option value='".$edition['id']."' data-price='".$edition['price']."'>"
                                            .$edition['name'].'--'.$edition['weight']."g - $".number_format($edition['price'], 2)
                                            ."</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <p><b>Catégorie :</b> 
                                <a href="category.php?category=<?php echo $product['cat_slug']; ?>" class="category-link">
                                    <?php echo $product['catname']; ?>
                                </a>
                            </p>
                            <p><b>Description :</b></p>
                            <p class="product-description"><?php echo $product['description']; ?></p>

                            <!-- Ajouter au panier -->
                            <form class="form-inline" id="productForm">
                                <div class="form-group">
                                    <div class="quantity-control">
                                        <div class="input-group">
                                            <input type="number" name="quantity" id="quantity" class="form-control text-center" 
                                                   value="1" min="1" max="<?php echo $product['qtty']; ?>" data-stock="<?php echo $product['qtty']; ?>">
                                            <span class="input-group-text">pièce(s)</span>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
                                    <button type="submit" class="cart-btn">
                                        <i class="fa fa-shopping-cart"></i> Ajouter au panier
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Commentaires Facebook -->
                <div class="comments-container">
                    <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" 
                         data-numposts="10" width="100%">
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php $pdo->close(); ?>
    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>

<script>
$(function(){
    // Mise à jour du prix lors du changement d'édition
    $('#edition').change(function(){
        var selectedPrice = $(this).find(':selected').data('price');
        if(selectedPrice) {
            $('#displayPrice').html('&#36; ' + parseFloat(selectedPrice).toFixed(2));
        }
    });

    $('#productForm').submit(function(e){
        e.preventDefault();
        var productId = $('input[name=id]').val();
        var quantity = parseInt($('#quantity').val());
        var stock = parseInt($('#quantity').data('stock'));
        var editionId = $('#edition').val();
        
        if(!editionId) {
            alert('Veuillez sélectionner un poids');
            return;
        }

        // Vérification de la quantité
        if(quantity > stock) {
            alert('La quantité disponible est de ' + stock + ' pièces');
            $('#quantity').val(stock);
            quantity = stock;
            return;
        }

        if(quantity < 1) {
            alert('La quantité minimum est 1');
            $('#quantity').val(1);
            quantity = 1;
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'cart_add.php',
            data: {
                id: productId,
                quantity: quantity,
                edition: editionId
            },
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    if(typeof(Storage) !== "undefined") {
                        // Récupérer le panier existant ou créer un nouveau
                        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                        
                        // Vérifier si le produit existe déjà
                        let existingItem = cart.find(item => item.edition_id === response.product.edition_id);
                        
                        if(existingItem) {
                            existingItem.quantity = quantity;
                            existingItem.price = response.product.price;
                        } else {
                            cart.push(response.product);
                        }
                        
                        // Sauvegarder le panier
                        localStorage.setItem('cart', JSON.stringify(cart));
                        
                        // Mettre à jour l'affichage du panier
                        updateCartCount(cart.length);
                    }
                    
                    alert('Produit ajouté au panier');
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Une erreur est survenue');
                console.error(error);
            }
        });
    });

    function updateCartCount(count) {
        // Mettre à jour le compteur du panier dans la navbar
        $('#cart-count').text(count);
    }
});
</script>

</body>
</html>