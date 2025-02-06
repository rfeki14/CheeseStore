<?php 
include 'includes/session.php';
include 'includes/header.php';  // Correction du point qui s'était glissé dans le nom du fichier
include 'includes/constants.php';

if(!isset($_SESSION['user'])) {
    header('location: login.php');
    exit();
}

if(!isset($_POST['total'])) {
    header('location: cart_view.php');
    exit();
}

$total = $_POST['total'];

// Récupération des adresses
$conn = $pdo->open();
try {
    // Correction de la vérification de l'ID utilisateur
    $user_id = (int)$_SESSION['user'];
    
    if ($user_id <= 0) {
        throw new Exception("Invalid user ID: " . var_export($_SESSION['user'], true));
    }
    
    // Debug de la session
    error_log("Session Debug - Raw user data: " . var_export($_SESSION['user'], true));
    error_log("Session Debug - Parsed user_id: " . $user_id);
    
    $stmt = $conn->prepare("
        SELECT a.* 
        FROM address a 
        INNER JOIN user_addresses ua ON a.id = ua.address_id 
        WHERE ua.user_id = :user_id
    ");
    
    $stmt->execute(['user_id' => $user_id]);
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("SQL Debug - Found " . count($addresses) . " addresses for user " . $user_id);
    
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    $addresses = [];
} catch(Exception $e) {
    error_log("Session Error: " . $e->getMessage());
    $addresses = [];
}

$pdo->close();

// Vérifier explicitement si nous avons des adresses
$hasAddresses = !empty($addresses);

// Amélioration du debug
error_log("Debug - User ID: " . $user_id);
error_log("Debug - Addresses: " . json_encode($addresses, JSON_PRETTY_PRINT));

// Formatage plus propre du var_dump
echo "<pre style='display:none;'>";
var_dump($user_id);
var_dump($addresses);
echo "</pre>";

?>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-header">Choose Delivery Method</h1>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <div class="box box-solid">
                            <div class="box-body">
                                <form action="process_delivery.php" method="POST" id="deliveryForm">
                                    <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">
                                    
                                    <div class="delivery-options">
                                        <div class="option">
                                            <input type="radio" name="delivery_method" id="pickup" value="pickup" required>
                                            <label for="pickup">
                                                <i class="fa fa-store"></i>
                                                Pickup from Store
                                                <small>Free</small>
                                            </label>
                                            
                                            <div class="store-selection" style="display:none;">
                                                <h4>Select Store Location:</h4>
                                                <select name="store_location" class="form-control">
                                                    <?php foreach(STORES as $key => $store): ?>
                                                        <option value="<?php echo $key; ?>">
                                                            <?php echo $store['name']; ?> - <?php echo $store['address']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="option">
                                            <input type="radio" name="delivery_method" id="delivery" value="delivery">
                                            <label for="delivery">
                                                <i class="fa fa-truck"></i>
                                                Home Delivery
                                                <small>+7.00 DT</small>
                                            </label>
                                            
                                            <div class="address-selection" style="display:none;">
                                                <h4>Select Delivery Address:</h4>
                                                <div class="address-list">
                                                    <?php if($hasAddresses): ?>
                                                        <?php foreach($addresses as $index => $row): ?>
                                                            <div class="address-item">
                                                                <input type="radio" name="address_id"
                                                                       value="<?php echo htmlspecialchars($row['id']); ?>"
                                                                       id="addr_<?php echo htmlspecialchars($row['id']); ?>"
                                                                       <?php echo ($index === 0) ? 'checked' : ''; ?>
                                                                       required>
                                                                <label for="addr_<?php echo htmlspecialchars($row['id']); ?>">
                                                                    <?php echo htmlspecialchars($row['street']); ?><br>
                                                                    <?php echo htmlspecialchars($row['city']) . ', ' . 
                                                                          htmlspecialchars($row['state']) . ' ' . 
                                                                          htmlspecialchars($row['zip_code']); ?><br>
                                                                    <?php echo htmlspecialchars($row['country']); ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        <!-- Bouton d'ajout d'adresse -->
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAddressModal">
                                                                <i class="fa fa-plus"></i> Add Another Address
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="no-address-message">No saved addresses. Please add a new address.</p>
                                                        <button type="button" class="btn btn-success mt-3" data-toggle="modal" data-target="#addAddressModal">
                                                            <i class="fa fa-plus"></i> Add New Address
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Continue to Payment
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Delivery Address</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="formMessage"></div>
                <form id="newAddressForm">
                    <div class="form-group">
                        <label>Street Address*</label>
                        <input type="text" class="form-control" name="street" required>
                    </div>
                    <div class="form-group">
                        <label>City*</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="form-group">
                        <label>State*</label>
                        <input type="text" class="form-control" name="state" required>
                    </div>
                    <div class="form-group">
                        <label>ZIP Code*</label>
                        <input type="text" class="form-control" name="zip_code" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>

<style>
.delivery-options {
    max-width: 600px;
    margin: 30px auto;
}
.delivery-options .option {
    margin: 20px 0;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    cursor: pointer;
}
.delivery-options .option:hover {
    background-color: #f8f9fa;
}
.delivery-options label {
    display: block;
    margin-left: 30px;
    font-size: 18px;
}
.delivery-options small {
    display: block;
    color: #666;
    margin-top: 5px;
}
.form-actions {
    text-align: center;
    margin-top: 30px;
}
.store-selection, .address-selection {
    margin-top: 15px;
    padding-left: 30px;
}

.address-list {
    margin-bottom: 20px;
}

.address-item {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 4px;
}

.address-item:hover {
    background-color: #f8f9fa;
}

.address-item input[type="radio"] {
    margin-right: 10px;
}

.address-item label {
    margin: 0;
    cursor: pointer;
    width: 100%;
}

.address-item p {
    margin: 2px 0;
}
</style>

<script>
$(function(){
    // Garder uniquement le code essentiel
    $('input[name="delivery_method"]').change(function() {
        $('.store-selection, .address-selection').hide();
        if($(this).val() === 'pickup') {
            $('.store-selection').slideDown();
        } else {
            $('.address-selection').slideDown();
        }
    });

    // Afficher les adresses si delivery est déjà sélectionné
    if($('#delivery').is(':checked')) {
        $('.address-selection').show();
    }

    // Si une erreur est présente, afficher le modal
    <?php if(isset($_SESSION['error'])): ?>
    $('#addAddressModal').modal('show');
    <?php endif; ?>

    // Gestionnaire du formulaire d'adresse
    $('#newAddressForm').on('submit', function(e){
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var $formMessage = $('#formMessage');
        
        // Désactiver le bouton pendant la soumission
        $submitBtn.prop('disabled', true);
        
        // Envoyer la requête AJAX
        $.ajax({
            url: 'address_add_ajax.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Créer le nouvel élément d'adresse
                    var newAddress = `
                        <div class="address-item">
                            <input type="radio" name="address_id" 
                                value="${response.address_id}" 
                                id="addr_${response.address_id}" 
                                checked required>
                            <label for="addr_${response.address_id}">
                                ${$form.find('[name="street"]').val()}<br>
                                ${$form.find('[name="city"]').val()}, 
                                ${$form.find('[name="state"]').val()} 
                                ${$form.find('[name="zip_code"]').val()}<br>
                                Tunisia
                            </label>
                        </div>`;

                    // Mettre à jour l'interface
                    if ($('.address-list').length) {
                        // Si la liste existe déjà
                        $('.no-address-message').remove();
                        $('.address-list').prepend(newAddress);
                    } else {
                        // Si c'est la première adresse
                        $('.address-selection').html(`
                            <div class="address-list">
                                ${newAddress}
                            </div>
                        `);
                    }

                    // Réinitialiser le formulaire et fermer le modal
                    $form[0].reset();
                    $('#addAddressModal').modal('hide');
                    
                    // Sélectionner l'option de livraison
                    $('#delivery').prop('checked', true).trigger('change');

                    // Afficher un message de succès temporaire
                    $('<div class="alert alert-success">').html('Address added successfully')
                        .insertBefore('.address-list')
                        .delay(3000)
                        .fadeOut(function() { $(this).remove(); });
                } else {
                    // Afficher le message d'erreur
                    $formMessage.html(`
                        <div class="alert alert-danger">
                            ${response.message || 'Error adding address'}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ajax error:', error);
                $formMessage.html(`
                    <div class="alert alert-danger">
                        Error adding address. Please try again.
                    </div>
                `);
            },
            complete: function() {
                // Réactiver le bouton
                $submitBtn.prop('disabled', false);
            }
        });
    });

    // Validation du formulaire de livraison
    $('#deliveryForm').on('submit', function(e) {
        var method = $('input[name="delivery_method"]:checked').val();
        
        if(!method) {
            e.preventDefault();
            alert('Please select a delivery method');
            return false;
        }
        
        if(method === 'delivery' && !$('input[name="address_id"]:checked').length) {
            e.preventDefault();
            alert('Please select a delivery address');
            return false;
        }
        
        if(method === 'pickup' && !$('select[name="store_location"]').val()) {
            e.preventDefault();
            alert('Please select a store location');
            return false;
        }
        
        return true;
    });
});
</script>
</body>
</html>
