<?php 
include 'includes/session.php';
include 'includes/header.php';
?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="box box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Modifier mon profil</h3>
                            </div>
                            <div class="box-body">
                                <?php
                                if(isset($_SESSION['error'])){
                                    echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                                    unset($_SESSION['error']);
                                }
                                if(isset($_SESSION['success'])){
                                    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
                                    unset($_SESSION['success']);
                                }
                                ?>
                                <form action="profile_edit.php" method="POST" enctype="multipart/form-data" id="profileForm">
                                    <div class="form-group">
                                        <label for="current_photo">Photo actuelle</label><br>
                                        <img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" width="150px">
                                    </div>
                                    <div class="form-group">
                                        <label for="photo">Nouvelle photo</label>
                                        <input type="file" class="form-control" id="photo" name="photo">
                                    </div>
                                    <div class="form-group">
                                        <label for="firstname">Prénom</label>
                                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lastname">Nom</label>
                                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contact">Contact</label>
                                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $user['contact_info']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="current_password">Mot de passe actuel (requis pour toute modification)</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" id="change_password" class="btn btn-info">Change password</button>
                                    </div>

                                    <div id="new_password_fields" style="display:none;">
                                        <div class="form-group">
                                            <label for="new_password">Nouveau mot de passe</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Adresses</label>
                                        <div id="addresses-container">
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
                                                    echo '<div class="address-block panel panel-default">';
                                                    echo '<div class="panel-heading">';
                                                    echo '<h4 class="panel-title">Adresse enregistré</h4>';
                                                    echo '</div>';
                                                    echo '<div class="panel-body">';
                                                    echo '<div class="row">';
                                                    echo '<input type="hidden"  name="addresses['.$row['id'].'][id]" value="'.$row['id'].'">';
                                                    
                                                    echo '<div class="col-md-12">';
                                                    echo '<div class="form-group">';
                                                    echo '<label>Rue</label>';
                                                    echo '<input type="text" class="form-control" name="addresses['.$row['id'].'][street]" value="'.$row['street'].'" placeholder="Rue" required readonly>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<div class="col-md-6">';
                                                    echo '<div class="form-group">';
                                                    echo '<label>Ville</label>';
                                                    echo '<input type="text" class="form-control" name="addresses['.$row['id'].'][city]" value="'.$row['city'].'" placeholder="Ville" readonly required>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<div class="col-md-6">';
                                                    echo '<div class="form-group">';
                                                    echo '<label>Code postal</label>';
                                                    echo '<input type="text" class="form-control" name="addresses['.$row['id'].'][zip_code]" value="'.$row['zip_code'].'" placeholder="Code postal" required readonly>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<div class="col-md-6">';
                                                    echo '<div class="form-group">';
                                                    echo '<label>État/Région</label>';
                                                    echo '<input type="text" class="form-control" name="addresses['.$row['id'].'][state]" value="'.$row['state'].'" placeholder="État/Région" readonly required>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '<div class="col-md-6">';
                                                    echo '<div class="form-group">';
                                                    echo '<label>Pays</label>';
                                                    echo '<input type="text" class="form-control" name="addresses['.$row['id'].'][country]" value="'.$row['country'].'" placeholder="Pays" readonly required>';
                                                    echo '</div>';
                                                    echo '</div>';

                                                    echo '</div>'; // End row
                                                    echo '<div class="text-right">';
                                                    echo '<button type="button" class="btn btn-danger remove-address"><i class="fa fa-trash"></i> Supprimer</button>';
                                                    echo '<button type="button" hidden class="btn btn-info edit-address" style="float:right"><i class="fa fa-edit"></i> Modifier</button>';
                                                    echo '</div>';
                                                    echo '</div>'; // End panel-body
                                                    echo '</div>'; // End panel
                                                }
                                            }
                                            catch(PDOException $e) {
                                                echo "Il y a un problème: " . $e->getMessage();
                                            }
                                            $pdo->close();
                                            ?>
                                        </div>
                                        <button type="button" id="add-address" class="btn btn-info">Ajouter une adresse</button>
                                    </div>

                                    <div class="form-group">
                                        <a href="profile.php" class="btn btn-default">Retour</a>
                                        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>
<script src="js/profile.js"></script>
<style>
    .address-block {
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .address-block .panel-heading {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }
    .address-block .form-group {
        margin-bottom: 15px;
    }
    .address-block .btn-danger {
        margin-top: 10px;
    }
    #add-address {
        margin-top: 20px;
        margin-bottom: 30px;
    }
    .box-body {
        padding: 20px;
    }
</style>
</body>
</html>