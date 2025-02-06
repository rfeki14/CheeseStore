<!-- Edit User Modal -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Modifier Utilisateur</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="users_edit.php">
                    <input type="hidden" class="userid" name="id">

                    <div class="form-group">
                        <label for="edit_email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_firstname" class="col-sm-3 control-label">Prénom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_firstname" name="firstname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_lastname" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_lastname" name="lastname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_contact" class="col-sm-3 control-label">Contact</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit_contact" name="contact">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_password" class="col-sm-3 control-label">Mot de passe</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="Laissez vide pour ne pas modifier">
                        </div>
                    </div>

                    <!-- Section Adresses -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Adresses</label>
                        <div class="col-sm-9">
                            <form id="addressForm" class="form-horizontal" method="POST" action="add_address.php">
                                <input type="hidden" name="user_id" class="userid">
                                <div id="addresses-container" class="addresses-wrapper">
                                    <!-- Les adresses seront ajoutées ici dynamiquement -->
                                </div>
                                <button type="button" class="btn btn-info btn-sm btn-add-address" id="add-address">
                                    <i class="fa fa-plus"></i> Ajouter une adresse
                                </button>
                            </form>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
                <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>