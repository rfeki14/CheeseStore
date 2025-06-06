
<?php 
include 'includes/session.php';
include 'includes/header.php';  
include 'includes/constants.php';
include 'includes/navbar.php';
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
    $user_id = (int)$_SESSION['user'];
    
    if ($user_id <= 0) {
        throw new Exception("ID utilisateur invalide : " . var_export($_SESSION['user'], true));
    }
    
    $stmt = $conn->prepare("
        SELECT a.* 
        FROM address a 
        INNER JOIN user_addresses ua ON a.id = ua.address_id 
        WHERE ua.user_id = :user_id
    ");
    
    $stmt->execute(['user_id' => $user_id]);
    $addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    error_log("Erreur de base de données : " . $e->getMessage());
    $addresses = [];
} catch(Exception $e) {
    error_log("Erreur de session : " . $e->getMessage());
    $addresses = [];
}
try {
    $stmt = $conn->prepare("SELECT s.id, s.name, a.street, a.city, a.zip_code, a.state, a.country FROM stores s, address a WHERE s.address = a.id");
    $stmt->execute();
    $stores = $stmt->fetchAll();
} catch(PDOException $e) {
    error_log("Erreur de base de données : " . $e->getMessage());
    $stores = [];
}

$pdo->close();

$hasAddresses = !empty($addresses);

?>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="page-header">Choisissez la méthode de livraison</h1>
                        <?php
                        if(isset($_SESSION['error'])) {
                            echo "
                                <div class='alert alert-danger'>
                                    {$_SESSION['error']}
                                </div>
                            ";
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
                                                Retrait en magasin
                                                <small>Gratuit</small>
                                            </label>
                                            
                                            <div class="store-selection" style="display:none;">
                                                <h4>Sélectionnez le lieu de retrait :</h4>
                                                <select name="store_location" class="form-control">
                                                    <?php foreach( $stores as $store): ?>
                                                        <option value="<?php echo $store['id']; ?>">
                                                            <?php echo $store['name']."-".$store['street']." ".$store['city']." ".$store['zip_code'].",".$store['state'].",".$store['country'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="option">
                                            <input type="radio" name="delivery_method" id="delivery" value="delivery">
                                            <label for="delivery">
                                                <i class="fa fa-truck"></i>
                                                Livraison à domicile
                                                <small>+7.00 DT</small>
                                            </label>
                                            
                                            <div class="address-selection" style="display:none;">
                                                <h4>Sélectionnez l'adresse de livraison :</h4>
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
                                                        <div class="mt-3">
                                                            <button type="button" class="btn btn-success mt3" data-toggle="modal" data-target="#addAddressModal">
                                                                <i class="fa fa-plus"></i> Ajouter une autre adresse
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="no-address-message">Aucune adresse enregistrée. Veuillez ajouter une nouvelle adresse.</p>
                                                        <button type="button" class="btn btn-success mt-3" data-toggle="modal" data-target="#addAddressModal">
                                                            <i class="fa fa-plus"></i> Ajouter une nouvelle adresse
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            Continuer vers le paiement
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

<!-- Modal pour ajouter une adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addAddressModalLabel">Ajouter une nouvelle adresse de livraison</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="formMessage"></div>
                <form id="newAddressForm">
                    <div class="form-group">
                        <label>Telephone*</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Adresse*</label>
                        <input type="text" class="form-control" name="street" required>
                    </div>
                    <div class="form-group">
                        <label>Ville*</label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="form-group">
                        <label>État*</label>
                        <input type="text" class="form-control" name="state" required>
                    </div>
                    <div class="form-group">
                        <label>Code postal*</label>
                        <input type="text" class="form-control" name="zip_code" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer l'adresse</button>
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
    background-color:rgba(26, 24, 24, 0.3);
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
body {
    font-family: 'Poppins', sans-serif;
    background-color: #1c252c; /* Dark background */
    color: #e0e0e0; /* Light text for readability */
}

/* Navbar */
.navbar {
    background: #1c252c; /* Dark navbar */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

</style>

<script>
$(function(){
    $('input[name="delivery_method"]').change(function() {
        $('.store-selection, .address-selection').hide();
        if($(this).val() === 'pickup') {
            $('.store-selection').slideDown();
        } else {
            $('.address-selection').slideDown();
        }
    });

    if($('#delivery').is(':checked')) {
        $('.address-selection').show();
    }

    $('#newAddressForm').on('submit', function(e){
        e.preventDefault();
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var $formMessage = $('#formMessage');
        
        $submitBtn.prop('disabled', true);
        
        $.ajax({
            url: 'address_add.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
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
                                Tunisie
                            </label>
                        </div>`;

                    if ($('.address-list').length) {
                        $('.no-address-message').remove();
                        $('.address-list').prepend(newAddress);
                    } else {
                        $('.address-selection').html(`
                            <div class="address-list">
                                ${newAddress}
                            </div>
                        `);
                    }

                    $form[0].reset();
                    $('#addAddressModal').modal('hide');
                    
                    $('#delivery').prop('checked', true).trigger('change');

                    $('<div class="alert alert-success">').html('Adresse ajoutée avec succès')
                        .insertBefore('.address-list')
                        .delay(3000)
                        .fadeOut(function() { $(this).remove(); });
                } else {
                    $formMessage.html(`
                        <div class="alert alert-danger">
                            ${response.message || 'Erreur lors de l\'ajout de l\'adresse'}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur Ajax :', error);
                $formMessage.html(`
                    <div class="alert alert-danger">
                        Erreur lors de l'ajout de l'adresse. Veuillez réessayer.
                    </div>
                `);
            },
            complete: function() {
                $submitBtn.prop('disabled', false);
            }
        });
    });

    $('#deliveryForm').on('submit', function(e) {
        var method = $('input[name="delivery_method"]:checked').val();
        
        if(!method) {
            e.preventDefault();
            alert('Veuillez sélectionner une méthode de livraison');
            return false;
        }
        
        if(method === 'delivery' && !$('input[name="address_id"]:checked').length) {
            e.preventDefault();
            alert('Veuillez sélectionner une adresse de livraison');
            return false;
        }
        
        if(method === 'pickup' && !$('select[name="store_location"]').val()) {
            e.preventDefault();
            alert('Veuillez sélectionner un lieu de retrait');
            return false;
        }
        
        return true;
    });
});
</script>
</body>
</html>