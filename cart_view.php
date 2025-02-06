<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="dist/css/cart_view.css">

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-header">YOUR CART</h1>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-bordered" id="cart_table">
                                    <thead>
                                        <tr>
                                            <th class="product-img">Photo</th>
                                            <th class="product-name">Name</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity" width="20%">Quantity</th>
                                            <th class="product-subtotal">Subtotal</th>
                                            <th class="product-actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        if(isset($_SESSION['user']) && isset($user) && $user !== false){
                                            try {
                                                $stmt = $conn->prepare("
                                                    SELECT c.id AS cartid, c.quantity, c.price AS cart_price,
                                                           e.price, e.weight,
                                                           p.name, p.photo, p.id AS product_id, p.qtty AS stock
                                                    FROM cart c
                                                    LEFT JOIN edition e ON e.id = c.edition_id 
                                                    LEFT JOIN products p ON p.id = e.product_id 
                                                    WHERE c.user_id=:user_id
                                                ");
                                                $stmt->execute(['user_id' => $user['id']]);
                                                
                                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                                    $image = !empty($row['photo']) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
                                                    $subtotal = $row['price'] * $row['quantity'];
                                                    $total += $subtotal;
                                                    ?>
                                                    <tr class="cart-item">
                                                        <td class="product-img">
                                                            <img src='<?php echo $image ?>' class="product-thumb">
                                                        </td>
                                                        <td class="product-name">
                                                            <span class="product-title"><?php echo htmlspecialchars($row['name']); ?></span>
                                                            <span class="product-weight"><?php echo $row['weight']; ?>g</span>
                                                        </td>
                                                        <td class="product-price"><?php echo number_format($row['price'], 2); ?> DT</td>
                                                        <td class="product-quantity">
                                                            <div class="quantity-control">
                                                                <button class="quantity-btn minus" data-id="<?php echo $row['cartid']; ?>">-</button>
                                                                <input type="number" class="quantity-input" 
                                                                    data-id="<?php echo $row['cartid']; ?>"
                                                                    data-stock="<?php echo $row['stock']; ?>"
                                                                    value="<?php echo min($row['quantity'], $row['stock']); ?>"
                                                                    min="1" 
                                                                    max="<?php echo $row['stock']; ?>"
                                                                >
                                                                <button class="quantity-btn plus" data-id="<?php echo $row['cartid']; ?>">+</button>
                                                            </div>
                                                        </td>
                                                        <td class="product-subtotal"><?php echo number_format($subtotal, 2); ?> DT</td>
                                                        <td class="product-actions">
                                                            <button class="cart_delete" data-id="<?php echo $row['cartid']; ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } catch(PDOException $e) {
                                                echo "<tr><td colspan='6' class='text-center'>Une erreur est survenue</td></tr>";
                                            }
                                        } 
                                        else if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                                            foreach($_SESSION['cart'] as $item){
                                                // Vérification et initialisation des valeurs
                                                $price = isset($item['price']) ? $item['price'] : 0;
                                                $quantity = isset($item['quantity']) ? $item['quantity'] : 0;
                                                $subtotal = $price * $quantity;
                                                $total += $subtotal;
                                                $image = !empty($item['photo']) ? 'images/'.$item['photo'] : 'images/noimage.jpg';
                                                ?>
                                                <tr class="cart-item">
                                                    <td class="product-img">
                                                        <img src='<?php echo htmlspecialchars($image); ?>' class="product-thumb">
                                                    </td>
                                                    <td class="product-name">
                                                        <span class="product-title"><?php echo htmlspecialchars($item['name'] ?? 'Produit inconnu'); ?></span>
                                                    </td>
                                                    <td class="product-price"><?php echo number_format($price, 2); ?> DT</td>
                                                    <td class="product-quantity">
                                                        <div class="quantity-control">
                                                            <button class="quantity-btn minus" data-id="<?php echo htmlspecialchars($item['edition_id'] ?? '0'); ?>">-</button>
                                                            <input type="number" class="quantity-input" 
                                                                data-id="<?php echo htmlspecialchars($item['edition_id'] ?? '0'); ?>"
                                                                value="<?php echo $quantity; ?>"
                                                                min="1" max="99">
                                                            <button class="quantity-btn plus" data-id="<?php echo htmlspecialchars($item['edition_id'] ?? '0'); ?>">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="product-subtotal"><?php echo number_format($subtotal, 2); ?> DT</td>
                                                    <td class="product-actions">
                                                        <button class="cart_delete" 
                                                                data-id="<?php echo htmlspecialchars($item['edition_id'] ?? '0'); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else {
                                            echo "<tr><td colspan='6' class='text-center'>Votre panier est vide</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-right"><b>Total</b></td>
                                            <td colspan="2"><b class="cart-total"><?php echo number_format($total, 2); ?> DT</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php if($total > 0): ?>
                                    <div class="checkout-section">
                                        <?php if(isset($_SESSION['user'])): ?>
                                            <form action='delivery_method.php' method='POST' class="checkout-form">
                                                <input type='hidden' name='total' id='hidden-total' value='<?php echo $total; ?>'>
                                                <div class="delivery-options text-center mb-3">
                                                    <button type='submit' class='btn btn-primary btn-lg checkout-btn'>
                                                        <i class="fa fa-truck"></i> Choose Delivery Method
                                                    </button>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="login-notice">
                                                <a href="login.php" class="btn btn-primary btn-lg checkout-btn">
                                                    <i class="fa fa-sign-in"></i> Login to Continue
                                                </a>
                                                <p class="text-muted mt-2">
                                                    <small>Please login first to proceed with your order.</small>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    // Charger les produits du panier local si l'utilisateur n'est pas connecté
    if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
        loadLocalCart();
    }

    function loadLocalCart() {
        if(typeof(Storage) !== "undefined") {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            if(cart.length > 0) {
                let cartHtml = '';
                let total = 0;
                
                cart.forEach(function(item) {
                    let subtotal = item.price * item.quantity;
                    total += subtotal;
                    cartHtml += generateCartItemHtml(item, subtotal);
                });

                $('#cart_table tbody').html(cartHtml);
                updateCartTotal(total);
            }
        }
    }

    function generateCartItemHtml(item, subtotal) {
        return `
            <tr class="cart-item">
                <td class="product-img">
                    <img src="images/${item.photo}" class="product-thumb">
                </td>
                <td class="product-name">
                    <span class="product-title">${item.name}</span>
                    <span class="product-weight">${item.weight}g</span>
                </td>
                <td class="product-price">${item.price.toFixed(2)} DT</td>
                <td class="product-quantity">
                    <div class="quantity-control">
                        <input type="number" class="quantity-input" 
                            data-id="${item.cartid}"
                            value="${item.quantity}"
                            min="1" max="${item.stock}">
                    </div>
                </td>
                <td class="product-subtotal">${subtotal.toFixed(2)} DT</td>
                <td class="product-actions">
                    <button class="cart_delete" data-id="${item.cartid}">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    $('#checkout').click(function(e){
        e.preventDefault();
        if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            // Sauvegarder l'état du panier
            localStorage.setItem('returnToCart', 'true');
            window.location.href = 'login.php';
        } else {
            window.location.href = 'checkout.php';
        }
    });

    $(document).on('change', '.quantity-input', function(e){
        e.preventDefault();
        var $input = $(this);
        var $row = $input.closest('tr');
        var id = $input.data('id');
        var qty = parseInt($input.val());
        var price = parseFloat($row.find('td:eq(2)').text().replace('DT', '').trim());
        var oldValue = $input.prop('defaultValue');

        // Vérifications de base
        if(isNaN(qty) || qty < 1) {
            alert('Quantité invalide');
            $input.val(oldValue);
            return;
        }

        if(<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            // Code existant pour utilisateur connecté
            $row.addClass('updating');
            $.ajax({
                type: 'POST',
                url: 'cart_update.php',
                data: { id: id, quantity: qty },
                dataType: 'json',
                success: function(response){
                    if(!response.error){
                        var subtotal = price * qty;
                        $row.find('td:eq(4)').text(subtotal.toFixed(2) + ' DT');
                        updateCartTotal();
                        $input.prop('defaultValue', qty);
                    } else {
                        alert(response.message);
                        $input.val(oldValue);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Update Error:', error);
                    alert('Erreur de mise à jour');
                    $input.val(oldValue);
                },
                complete: function() {
                    $row.removeClass('updating');
                }
            });
        } else {
            // Mise à jour pour le panier local
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            let itemIndex = cart.findIndex(item => item.edition_id == id);
            
            if (itemIndex !== -1) {
                cart[itemIndex].quantity = qty;
                localStorage.setItem('cart', JSON.stringify(cart));
                
                var subtotal = price * qty;
                $row.find('td:eq(4)').text(subtotal.toFixed(2) + ' DT');
                updateCartTotal();
                $input.prop('defaultValue', qty);
            }
        }
    });

    $(document).on('click', '.cart_delete', function(e){
        e.preventDefault();
        var button = $(this);
        var id = button.data('id');
        
        if(confirm('Êtes-vous sûr de vouloir supprimer cet article ?')){
            if(<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
                // Code existant pour utilisateur connecté
                $.ajax({
                    type: 'POST',
                    url: 'cart_delete.php',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response){
                        if(!response.error){
                            button.closest('tr').fadeOut(300, function(){
                                $(this).remove();
                                updateCartTotal();
                                if($('#cart_table tbody tr').length === 0){
                                    location.reload();
                                }
                            });
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Une erreur est survenue lors de la suppression');
                        console.error(error);
                    }
                });
            } else {
                // Suppression pour le panier local
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                let newCart = cart.filter(item => item.edition_id != id);
                localStorage.setItem('cart', JSON.stringify(newCart));
                
                button.closest('tr').fadeOut(300, function(){
                    $(this).remove();
                    updateCartTotal();
                    if($('#cart_table tbody tr').length === 0){
                        location.reload();
                    }
                });
            }
        }
    });

    // Amélioration de la fonction updateCartTotal
    function updateCartTotal() {
        var total = 0;
        $('#cart_table tbody tr').each(function(){
            var price = parseFloat($(this).find('td:eq(2)').text().replace('DT', '').trim());
            var quantity = parseInt($(this).find('.quantity-input').val());
            if(!isNaN(price) && !isNaN(quantity)) {
                total += price * quantity;
            }
        });
        $('#cart_table tfoot b').text(total.toFixed(2) + ' DT');
        $('#hidden-total').val(total.toFixed(2));
        
        // Sauvegarder le total dans une session
        $.post('save_cart_total.php', {total: total});
    }

    // Gestionnaire des boutons plus/moins
    $(document).on('click', '.quantity-btn', function() {
        var input = $(this).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        if ($(this).hasClass('plus')) {
            input.val(currentVal + 1).trigger('change');
        } else {
            if (currentVal > 1) {
                input.val(currentVal - 1).trigger('change');
            }
        }
    });

    // Initialisation du panier local si pas de session
    if (!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (cart.length === 0) {
            $('#cart_table tbody').html('<tr><td colspan="6" class="text-center">Votre panier est vide</td></tr>');
        }
    }
    
    // Gestionnaire pour le bouton de checkout
    $('#login-checkout').click(function(e) {
        e.preventDefault();
        // Sauvegarder le panier dans localStorage
        localStorage.setItem('returnToCart', 'true');
        localStorage.setItem('cartTotal', $('#hidden-total').val());
        window.location.href = 'login.php';
    });

    // Ajouter ce gestionnaire d'événements
    $('.checkout-btn').click(function(e) {
        if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            e.preventDefault();
            // Sauvegarder l'état du panier
            localStorage.setItem('returnToCart', 'true');
            localStorage.setItem('cartTotal', $('.cart-total').text().replace(' DT', ''));
            window.location.href = 'login.php';
        }
    });
});
</script>

<script>
$(function(){
    // Fonction pour charger le panier local
    function loadLocalCart() {
        if(typeof(Storage) !== "undefined") {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            if(cart.length > 0) {
                let cartHtml = '';
                let total = 0;
                
                cart.forEach(function(item) {
                    let subtotal = item.price * item.quantity;
                    total += subtotal;
                    cartHtml += `
                        <tr class="cart-item">
                            <td class="product-img">
                                <img src="images/${item.photo}" class="product-thumb">
                            </td>
                            <td class="product-name">
                                <span class="product-title">${item.name}</span>
                                <span class="product-weight">${item.weight}g</span>
                            </td>
                            <td class="product-price">${item.price.toFixed(2)} DT</td>
                            <td class="product-quantity">
                                <div class="quantity-control">
                                    <button class="quantity-btn minus" data-id="${item.edition_id}">-</button>
                                    <input type="number" class="quantity-input" 
                                        data-id="${item.edition_id}"
                                        value="${item.quantity}"
                                        min="1" 
                                        max="${item.stock}">
                                    <button class="quantity-btn plus" data-id="${item.edition_id}">+</button>
                                </div>
                            </td>
                            <td class="product-subtotal">${subtotal.toFixed(2)} DT</td>
                            <td class="product-actions">
                                <button class="cart_delete" data-id="${item.edition_id}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#cart_table tbody').html(cartHtml);
                updateCartTotal();
            } else {
                $('#cart_table tbody').html('<tr><td colspan="6" class="text-center">Votre panier est vide</td></tr>');
            }
        }
    }

    // Gestionnaire de quantité pour le panier local
    $(document).on('change', '.quantity-input', function(e) {
        e.preventDefault();
        var $input = $(this);
        var id = $input.data('id');
        var qty = parseInt($input.val());
        
        if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            updateLocalCartQuantity(id, qty);
        }
    });

    // Fonction de mise à jour de la quantité dans le panier local
    function updateLocalCartQuantity(id, qty) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let item = cart.find(item => item.edition_id == id);
        
        if(item) {
            item.quantity = qty;
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Mise à jour de l'affichage
            let price = parseFloat(item.price);
            let subtotal = price * qty;
            let $row = $(`.quantity-input[data-id="${id}"]`).closest('tr');
            $row.find('.product-subtotal').text(subtotal.toFixed(2) + ' DT');
            updateCartTotal();
        }
    }

    // Gestionnaire de suppression pour le panier local
    $(document).on('click', '.cart_delete', function(e) {
        e.preventDefault();
        var button = $(this);
        var id = button.data('id');
        
        if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            if(confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                removeFromLocalCart(id);
                button.closest('tr').fadeOut(300, function() {
                    $(this).remove();
                    updateCartTotal();
                    if($('#cart_table tbody tr').length === 0) {
                        location.reload();
                    }
                });
            }
        }
    });

    // Fonction de suppression du panier local
    function removeFromLocalCart(id) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart = cart.filter(item => item.edition_id != id);
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Mise à jour du total
    function updateCartTotal() {
        var total = 0;
        $('#cart_table tbody tr.cart-item').each(function() {
            var price = parseFloat($(this).find('.product-price').text().replace('DT', '').trim());
            var quantity = parseInt($(this).find('.quantity-input').val());
            if(!isNaN(price) && !isNaN(quantity)) {
                total += price * quantity;
            }
        });
        $('.cart-total').text(total.toFixed(2) + ' DT');
        
        if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
            localStorage.setItem('cartTotal', total.toFixed(2));
        }
    }

    // Initialisation
    if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
        loadLocalCart();
    }

    // Gestionnaire du checkout
    $('#checkout').click(function(e) {
        e.preventDefault();
        <?php if(!isset($_SESSION['user'])): ?>
            // Sauvegarder l'URL actuelle pour redirection après login
            localStorage.setItem('returnToCart', 'true');
            window.location.href = 'login.php';
        <?php else: ?>
            window.location.href = 'checkout.php';
        <?php endif; ?>
    });
});
</script>
<style>
.updating {
    opacity: 0.5;
    pointer-events: none;
}
.login-notice {
    text-align: center;
}
.login-notice p {
    margin-top: 10px;
}
.checkout-section {
    margin-top: 20px;
    text-align: center;
}
.checkout-btn {
    min-width: 200px;
}
.login-notice {
    margin: 20px 0;
}
.login-notice p {
    margin-top: 10px;
}
.login-notice a {
    color: #3c8dbc;
    text-decoration: underline;
}
.checkout-section {
    margin-top: 20px;
    text-align: center;
}
.checkout-btn {
    min-width: 200px;
    margin-bottom: 10px;
}
.login-notice {
    margin: 20px 0;
    text-align: center;
}
.login-notice p {
    margin-top: 10px;
    color: #666;
}
.login-notice a {
    color: #3c8dbc;
    text-decoration: none;
}
.login-notice a:hover {
    text-decoration: underline;
}
.checkout-section {
    margin-top: 20px;
    text-align: center;
}
.checkout-btn {
    min-width: 250px;
    margin-bottom: 10px;
    font-size: 16px;
    padding: 12px 24px;
}
.delivery-options {
    margin-top: 20px;
}
</style>
</body>
</html>