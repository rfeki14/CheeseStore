<?php include 'includes/session.php'; ?>
<?php
$where = '';
$prodid = 0;
$catid = 0;

if (isset($_GET['product'])) {
    $prodid = intval($_GET['product']);
    if ($prodid == -1) {
        $where = 'WHERE edition.product_id IS NULL';
    } else {
        $where = 'WHERE edition.product_id =' . $prodid;
    }
}

if (isset($_GET['category'])) {
    $catid = intval($_GET['category']);
    if ($catid != 0) {
        $where .= ($where == '') ? 'WHERE ' : ' AND ';
        $where .= 'products.category_id =' . $catid;
    }
}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Conteneur de contenu. Contient le contenu de la page -->
    <div class="content-wrapper">
        <!-- En-tête de contenu (en-tête de page) -->
        <section class="content-header">
            <h1>
                Liste des Éditions
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
                <li>Éditions</li>
                <li class="active">Liste des Éditions</li>
            </ol>
        </section>

        <!-- Contenu principal -->
        <section class="content">
            <?php
            if (isset($_SESSION['error'])) {
                echo "
                        <div class='alert alert-danger alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h4><i class='icon fa fa-warning'></i> Erreur!</h4>
                            " . $_SESSION['error'] . '
                        </div>
                    ';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo "
                        <div class='alert alert-success alert-dismissible'>
                            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                            <h4><i class='icon fa fa-check'></i> Succès!</h4>
                            " . $_SESSION['success'] . '
                        </div>
                    ';
                unset($_SESSION['success']);
            }
            ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <a href="#addnew" data-toggle="modal" class="btn btn-success btn-sm rounded-pill" id="addedition">
                                <i class="fa fa-plus"></i> Nouvelle Édition
                            </a>
                            <div class="pull-right">
                                <form class="form-inline">
                                    <div class="form-group">
                                        <label>Produit: </label>
                                        <select class="form-control input-sm" id="select_product">
                                            <option value="0" <?php echo ($prodid == 0) ? 'selected' : ''; ?>>TOUS</option>
                                            <option value="-1" <?php echo ($prodid == -1) ? 'selected' : ''; ?>>Aucun</option>
                                            <?php
                                            $conn = $pdo->open();

                                            $stmt = $conn->prepare('SELECT * FROM products');
                                            $stmt->execute();

                                            foreach ($stmt as $prow) {
                                                $selected = ($prow['id'] == $prodid) ? 'selected' : '';
                                                echo "
                                                        <option value='" . $prow['id'] . "' " . $selected . '>' . $prow['name'] . '</option>
                                                    ';
                                            }

                                            $pdo->close();
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Catégorie: </label>
                                        <select class="form-control input-sm" id="select_category">
                                            <option value="0" <?php echo ($catid == 0) ? 'selected' : ''; ?>>TOUS</option>
                                            <?php
                                            $conn = $pdo->open();

                                            $stmt = $conn->prepare('SELECT * FROM category');
                                            $stmt->execute();

                                            foreach ($stmt as $crow) {
                                                $selected = ($crow['id'] == $catid) ? 'selected' : '';
                                                echo "
                                                        <option value='" . $crow['id'] . "' " . $selected . '>' . $crow['name'] . '</option>
                                                    ';
                                            }

                                            $pdo->close();
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="box-body">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <th>Nom</th>
                                    <th>Produit</th>
                                    <th>Poids</th>
                                    <th>Prix</th>
                                    <th>Outils</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $conn = $pdo->open();

                                        try {
                                            $stmt = $conn->prepare("SELECT edition.*, products.name AS prodname FROM edition LEFT JOIN products ON products.id=edition.product_id $where");
                                            $stmt->execute();
                                            foreach ($stmt as $row) {
                                                $product = (!empty($row['prodname'])) ? htmlspecialchars($row['prodname']) : 'Aucun';
                                                echo "
                                                        <tr>
                                                            <td>" . htmlspecialchars($row['name']) . "</td>
                                                            <td>" . $product . "</td>
                                                            <td> " . number_format($row['weight'], 2) . '</td>
                                                            <td>&#36;' . number_format($row['price'], 2) . "</td>
                                                            <td>
                                                                <div class='btn-group'>
                                                                    <button class='btn btn-primary btn-sm edit rounded-pill' data-id='" . $row['id'] . "'>
                                                                        <i class='fa fa-edit'></i> Modifier
                                                                    </button>
                                                                    <button class='btn btn-danger btn-sm delete rounded-pill ms-2' data-id='" . $row['id'] . "'>
                                                                        <i class='fa fa-trash'></i> Supprimer
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    ";
                                            }
                                        } catch (PDOException $e) {
                                            echo $e->getMessage();
                                        }

                                    $pdo->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
         
    </div>
        <?php include 'includes/footer.php'; ?>
        <?php include 'includes/editions_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    $(document).on('click', '.edit', function(e){
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
    });

    $(document).on('click', '.delete', function(e){
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
    });
    
    $(document).on('click', '.desc', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
    });

    $('#select_product').change(function(){
        var val = $(this).val();
        var cat = $('#select_category').val();
        if(val == 0){
            window.location = 'editions.php?category=' + cat;
        }
        else if(val == -1){
            window.location = 'editions.php?product=-1&category=' + cat;
        }
        else{
            window.location = 'editions.php?product='+val+'&category=' + cat;
        }
    });

    $('#select_category').change(function(){
        var val = $(this).val();
        var prod = $('#select_product').val();
        if(val == 0){
            window.location = 'editions.php?product=' + prod;
        }
        else if(val==-1){
            window.location ='editions.php?category='+-1+'&product='+prod;
        }
        else{
            window.location = 'editions.php?category='+val+'&product=' + prod;
        }
    });

    $('#addedition').click(function(e){
        e.preventDefault();
        getProduct();
    });

    $("#addnew").on("hidden.bs.modal", function () {
            $('.append_items').remove();
    });

    $("#edit").on("hidden.bs.modal", function () {
            $('.append_items').remove();
    });

});

function getRow(id) {
        console.log("Récupération des détails de l'édition pour l'ID:", id); // Journal de débogage

        $.ajax({
                type: 'POST',
                url: 'editions_row.php',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                        console.log("Données de l'édition reçues:", response); // Journal de débogage

                        if (response) {
                                // Mettre à jour le champ d'entrée ID caché
                                $('.editionid').val(response.editionid);

                                // Mettre à jour les modaux de vue et d'édition avec les détails de l'édition
                                $('.name').html("ID: " + response.editionid + " - " + response.editionname);
                                $('#edit_name').val(response.editionname);
                                $('#edit_price').val(response.price);
                                $('#edit_weight').val(response.weight);
                                $('#edit_product').val(response.product_id);

                                // Charger les options de produit et sélectionner le bon
                                getProduct(response.product_id);

                        } else {
                                console.error("Réponse invalide:", response);
                        }
                },
                error: function(xhr, status, error) {
                        console.error("Erreur AJAX:", error);
                }
        });
}

function getProduct(selectedProductId = null) {
        $.ajax({
                type: 'POST',
                url: 'product_fetch.php', // Récupérer les produits de la base de données
                dataType: 'json',
                success: function(response) {
                        console.log("Données de produit reçues:", response); // Journal de débogage

                        if (Array.isArray(response)) {
                                let productOptions = '<option value="">Sélectionner un produit</option>';
                                response.forEach(function(product) {
                                        productOptions += `<option value="${product.id}">${product.name}</option>`;
                                });

                                // Remplir les listes déroulantes d'ajout et d'édition
                                $('#product').html(productOptions);
                                $('#product').val(selectedProductId);
                                $('#edit_product').html(productOptions);
                                $('#edit_product').val(selectedProductId);

                                // Définir le produit sélectionné APRÈS que les options soient peuplées
                                if (selectedProductId) {
                                        $('#edit_product').val(selectedProductId);
                                }
                        } else {
                                console.error("Format de réponse invalide:", response);
                        }
                },
                error: function(xhr, status, error) {
                        console.error("Erreur AJAX (Produits):", error);
                }
        });
}
</script>
<style>
.btn {
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
}

.btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-group {
        gap: 8px;
}

.rounded-pill {
        border-radius: 50px;
}

.btn-success {
        background-color: #28a745;
        border-color: #28a745;
}

.btn-primary {
        background-color: #007bff;
        border-color: #007bff;
}

.btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
}

.btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
}
</style>
</body>
</html>