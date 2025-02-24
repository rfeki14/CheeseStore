<?php include 'includes/session.php'; ?>
<?php
    if(!isset($_SESSION['user'])){
        header('location: index.php');
    }
?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="dist/css/cart_view.css">
<style>
/* Styles généraux */
body {
    font-family: 'Arial', sans-serif;
    background-color: #1d232a;
}

/* Styles pour la carte de profil */
.profile-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 20px; /* Réduire la marge en bas */
    padding: 15px; /* Réduire le padding */
    max-width: 600px; /* Limiter la largeur maximale */
    margin-left: auto;
    margin-right: auto;
}

.profile-header {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.profile-image {
    border-radius: 50%;
    border: 3px solid #fff; /* Réduire l'épaisseur de la bordure */
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 80px; /* Réduire la largeur de l'image */
    height: 80px; /* Réduire la hauteur de l'image */
    object-fit: cover;
    margin-bottom: 15px; /* Réduire la marge en bas */
}

.profile-info {
    text-align: center;
}

.profile-info h4 {
    margin-bottom: 8px; /* Réduire la marge en bas */
    color: #333;
    font-size: 14px; /* Réduire la taille de la police */
}

.profile-info .row {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile-info .col-sm-3,
.profile-info .col-sm-9 {
    width: 100%;
    text-align: center;
}

.profile-info .col-sm-3 h4 {
    font-weight: bold;
    margin-bottom: 4px; /* Réduire la marge en bas */
}

.profile-info .col-sm-9 h4 {
    margin-bottom: 10px; /* Réduire la marge en bas */
}

.addresses-list {
    margin-top: 8px; /* Réduire la marge en haut */
}

.address-item {
    margin-bottom: 8px; /* Réduire la marge en bas */
    padding: 8px; /* Réduire le padding */
    background: #f9f9f9;
    border-radius: 6px; /* Réduire le rayon de la bordure */
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.address-item p {
    margin: 0;
    font-size: 12px; /* Réduire la taille de la police */
    color: #555;
}

.btn-success.btn-flat {
    background: #28a745;
    border: none;
    padding: 6px 12px; /* Réduire le padding */
    border-radius: 4px;
    color: #fff;
    transition: all 0.3s;
}

.btn-success.btn-flat:hover {
    background: #218838;
    transform: translateY(-1px);
}

/* Styles pour la carte des transactions */
.transaction-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.transaction-header {
    border-bottom: 2px solid #f4f4f4;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

#example1 {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    margin-bottom: 20px;
}

#example1 thead th {
    background: #f8f9fa;
    padding: 12px;
    color: #444;
}

#example1 tbody tr {
    background: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

#example1 tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#example1 td {
    padding: 12px;
    vertical-align: middle;
}

.btn-info.transact {
    background: #3498db;
    border: none;
    padding: 8px 15px;
    transition: all 0.3s;
}

.btn-info.transact:hover {
    background: #2980b9;
    transform: translateY(-1px);
}

/* Styles de pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination > li > a,
.pagination > li > span {
    border: none;
    color: #555;
    margin: 0 5px;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    padding: 0;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.pagination > li > a:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
}

.pagination > .active > a,
.pagination > .active > span {
    background-color: #3498db !important;
    color: white;
    border: none;
}

/* Responsive Design pour les écrans mobiles */
@media (max-width: 768px) {
    .profile-header {
        padding: 15px;
    }

    .profile-image {
        width: 80px;
        height: 80px;
    }

    .profile-info h4 {
        font-size: 14px;
    }

    .address-item p {
        font-size: 12px;
    }

    .btn-success.btn-flat {
        width: 100%;
        margin-top: 10px;
    }

    #example1 {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    .transaction-card, .profile-card {
        margin: 10px;
        padding: 10px;
    }

    .modal-dialog {
        margin: 10px;
    }

    .modal-content {
        padding: 15px;
    }

    h4 {
        font-size: 16px;
    }

    .btn, .btn-flat {
        padding: 5px 10px;
        font-size: 12px;
    }
}

.text-black {
    color: black; /* Ou utilisez #000000 pour le noir */
}
</style>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
     
    <div class="content-wrapper">
        <div class="container">

            <!-- Contenu principal -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-9">
                        <?php
                            if(isset($_SESSION['error'])){
                                echo "
                                    <div class='callout callout-danger'>
                                        ".$_SESSION['error']."
                                    </div>
                                ";
                                unset($_SESSION['error']);
                            }

                            if(isset($_SESSION['success'])){
                                echo "
                                    <div class='callout callout-success'>
                                        ".$_SESSION['success']."
                                    </div>
                                ";
                                unset($_SESSION['success']);
                            }
                        ?>
                       <div class="box box-solid profile-card">
    <div class="box-body profile-header">
        <div class="profile-image-container">
            <img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="profile-image">
        </div>
        <div class="profile-info">
            <div class="row">
                <div class="col-sm-12">
                    <h4>
                        <?php echo $user['firstname'].' '.$user['lastname']; ?>
                        <span class="pull-right">
                            <a href="edit_profile.php" class="btn btn-success btn-flat btn-sm"><i class="fa fa-edit"></i> Modifier</a>
                        </span>
                    </h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>Nom</th>
                            <td><?php echo $user['firstname'].' '.$user['lastname']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $user['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Informations de contact</th>
                            <td><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/a'; ?></td>
                        </tr>
                        <tr>
                            <th>Adresses</th>
                            <td>
                                <div class="addresses-list">
                                    <?php
                                    $conn = $pdo->open();
                                    try {
                                        $stmt = $conn->prepare("
                                            SELECT a.* 
                                            FROM address a 
                                            JOIN user_addresses ua ON a.id = ua.address_id 
                                            WHERE ua.user_id = :user_id
                                        ");
                                        $stmt->execute(['user_id' => $user['id']]);
                                        
                                        while($row = $stmt->fetch()) {
                                            echo '<div class="address-item">';
                                            echo '<p>' . $row['street'] . '<br>';
                                            echo $row['city'] . ', ' . $row['state'] . ' ' . $row['zip_code'] . '<br>';
                                            echo $row['country'] . '</p>';
                                            echo '</div>';
                                        }
                                    }
                                    catch(PDOException $e) {
                                        echo "Il y a un problème: " . $e->getMessage();
                                    }
                                    $pdo->close();
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
                        <div class="box box-solid transaction-card">
                            <div class="transaction-header">
                            <h4 class="box-title text-black"><i class="fa fa-calendar"></i> <b>Historique des transactions</b></h4> </div>
                            <div class="box-body">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <th class="hidden"></th>
                                        <th>Date</th>
                                        <th>Transaction#</th>
                                        <th>Montant</th>
                                        <th>Détails complets</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $conn = $pdo->open();

                                        try{
                                            $stmt = $conn->prepare("SELECT * FROM sales WHERE user_id=:user_id ORDER BY id DESC");
                                            $stmt->execute(['user_id'=>$user['id']]);
                                            foreach($stmt as $row){
                                                $total = $row['total'];
                                                echo "
                                                    <tr>
                                                        <td class='hidden'></td>
                                                        <td>".date('M d, Y', strtotime($row['sales_date']))."</td>
                                                        <td>".$row['id']."</td>
                                                        <td>&#36; ".number_format($total, 2)."</td>
                                                        <td><button class='btn btn-sm btn-flat btn-info transact' data-id='".$row['id']."'><i class='fa fa-search'></i> Voir</button></td>
                                                    </tr>
                                                ";
                                            }

                                        }
                                        catch(PDOException $e){
                                            echo "Il y a un problème de connexion : " . $e->getMessage();
                                        }

                                        $pdo->close();
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
                </div>
            </section>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/profile_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    // Gestionnaire unique pour le formulaire Edit
    $('#edit-form').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            type: 'POST',
            url: 'profile_edit.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log('Response:', response);
                if(response.trim() === 'success'){
                    location.reload();
                } else {
                    alert('Erreur : ' + response);
                }
            }
        });
    });

    // Gestionnaire des boutons Close
    $('.modal .close, .modal .btn-default').click(function(){
        $(this).closest('.modal').modal('hide');
    });

    // Reste du code pour les transactions
    $(document).on('click', '.transact', function(e){
        e.preventDefault();
        $('#transaction').modal('show');
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'transaction.php',
            data: {id:id},
            dataType: 'json',
            success:function(response){
                $('#date').html(response.date);
                $('#transid').html(response.transaction);
                $('#status').html(response.status);
                $('#delivery').html(response.delivery_method);
                $('#address').html(response.address);
                $('#detail').prepend(response.list);
                if(response.fee!=0){
                    $('#dfee').show();
                    $('#fee').html(response.fee);
                };
                $('#total').html(response.total);
            }
        });
    });

    $("#transaction").on("hidden.bs.modal", function () {
        $('.prepend_items').remove();
        $('#dfee').hide();
        $('#fee').html(0);
    });

    // Supprimer tous les autres gestionnaires de formulaire et garder uniquement celui-ci
    $('#edit-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'profile_edit.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                console.log('Server response:', response); // Debug
                if(response.trim() == 'success'){
                    alert('Profil mis à jour avec succès');
                    $('#edit').modal('hide');
                    window.location.reload();
                } else {
                    alert('Erreur lors de la mise à jour du profil : ' + response);
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur : ' + error);
            }
        });
        return false;
    });
});
</script>
</body>
</html>
