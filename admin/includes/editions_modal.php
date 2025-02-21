<!-- Ajouter -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Ajouter une nouvelle édition</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="edition_add.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product" class="col-sm-3 control-label">Produit</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="product" name="product" required>
                                <!-- Les options seront peuplées par JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-3 control-label">Prix</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-sm-3 control-label">Poids</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="weight" name="weight" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <input name="add" type="text" hidden value="1">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Modifier l'édition</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="edition_edit.php" enctype="multipart/form-data">
                    <input type="hidden" class="editionid" name="id">
                    <div class="form-group">
                        <label for="edit_name" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_product" class="col-sm-3 control-label">Produit</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="edit_product" name="product" required>
                                <!-- Les options seront peuplées par JavaScript -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_price" class="col-sm-3 control-label">Prix</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="edit_price" name="price" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_weight" class="col-sm-3 control-label">Poids</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="edit_weight" name="weight" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Supprimer -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><b>Suppression...</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="edition_delete.php">
                    <input type="hidden" class="editionid" name="id">
                    <div class="text-center">
                        <p>SUPPRIMER L'ÉDITION</p>
                        <h2 class="bold name"></h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>