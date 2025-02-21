<!-- Description -->
<!-- Vue Modale -->
<!-- Product Description Modal -->
<div class="modal fade" id="description" tabindex="-1" role="dialog" aria-labelledby="descriptionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ajouter un nouveau produit</b></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="product_image" src="../images/noimage.jpg" class="img-fluid" alt="Product Image" width="250px" height="250px">
                        </div>
                        <div class="col-md-8">
                            <div id="product_details">
                                <p><strong>Nom:</strong> <span id="product_name"></span></p>
                                <p><strong>Catégorie:</strong> <span id="product_category"></span></p>
                                <p><strong>Quantité:</strong> <span id="product_quantity"></span></p>
                                <p><strong>Description:</strong></p>
                                <p id="product_description"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function populateDescriptionModal(response) {
        $('#product_name').text(response.prodname);
        $('#product_category').text(response.category_name || 'Aucune');
        $('#product_quantity').text(response.qtty);
        $('#product_description').text(response.description);
        $('#product_image').attr('src', response.photo || '../images/noimage.jpg');
    }
</script>

<!-- Ajouter -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ajouter un nouveau produit</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="products_add.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="category" name="category" required>
                                <!-- Les options seront peuplées par JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label">Quantité</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="quantity" name="qtty" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control photo-input" name="photo" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Mettre à jour la photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="name"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="prodid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control photo-input" name="photo">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Suppression -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Suppression...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="products_delete.php">
                <input type="hidden" class="prodid" name="id"> <!-- Assurez-vous que cette entrée existe -->
                <div class="text-center">
                    <p>SUPPRIMER LE PRODUIT</p>
                    <h2 class="bold name"></h2> <!-- Cela affichera le nom du produit -->
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Supprimer</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Modifier -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Modifier le produit</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="products_edit.php" enctype="multipart/form-data">
                    <input type="hidden" class="prodid" name="id">
                    <div class="form-group">
                        <label for="edit_name" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_category" class="col-sm-3 control-label">Catégorie</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="edit_category" name="category" required>
                                <!-- Les options seront peuplées par JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_quantity" class="col-sm-3 control-label">Quantité</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_quantity" name="qtty" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control photo-input" name="photo">
                            <div class="old-image-container">
                                <img src="../images/noimage.jpg" id="old_photo_preview" alt="Ancienne image" width="100px">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_description" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>