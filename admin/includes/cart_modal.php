<!-- Ajouter -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Ajouter un nouveau produit</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="cart_add.php">
                <input type="hidden" class="userid" name="id">
                <div class="form-group">
                    <label for="product" class="col-sm-3 control-label">Produit</label>

                    <div class="col-sm-9">
                      <select class="form-control select2" style="width: 100%;" name="product" id="product" required>
                        <option value="" selected>- Sélectionner -</option>
                      </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quantity" class="col-sm-3 control-label">Quantité</label>

                    <div class="col-sm-9">
                      <input type="number" class="form-control" id="quantity" name="quantity" value="1" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
              <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Enregistrer</button>
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
              <h4 class="modal-title"><b><span class="productname"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="cart_edit.php">
                <input type="hidden" class="cartid" name="cartid">
                <input type="hidden" class="userid" name="userid">
                <div class="form-group">
                    <label for="edit_quantity" class="col-sm-3 control-label">Quantité</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="edit_quantity" name="quantity">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
              <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Mettre à jour</button>
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
              <form class="form-horizontal" method="POST" action="cart_delete.php">
                <input type="hidden" class="cartid" name="cartid">
                <input type="hidden" class="userid" name="userid">
                <div class="text-center">
                    <p>SUPPRIMER LE PRODUIT</p>
                    <h2 class="bold productname"></h2>
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