<!-- Add New User Modal -->
<div class="modal fade" id="adduserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b>Ajouter un nouvel utilisateur</b></h4>
            </div>
            <div class="modal-body">
                <form id="addnewuser" class="form-horizontal" method="POST" action="users_add.php" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="">
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" required placeholder="exemple@mail.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Mot de passe</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Minimum 8 caractères">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firstname" class="col-sm-3 control-label">Prénom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="Votre prénom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-sm-3 control-label">Nom</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="lastname" name="lastname" required placeholder="Votre nom">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact" class="col-sm-3 control-label">Contact</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="contact" name="contact" placeholder="Numéro de téléphone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" id="photo" name="photo">
                        </div>
                    </div>

                    <!-- Section Adresses -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Adresses</label>
                        <div class="col-sm-9">
                            <div id="addresses-container">
                            <input type="hidden" name="address_ids" id="address_ids" value="">

                                <!-- Les adresses seront ajoutées ici dynamiquement -->
                            </div>
                            <button type="button" class="btn btn-info btn-sm btn-add-address" id="add-address">
                                <i class="fa fa-plus"></i> Ajouter une adresse
                            </button>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
                        <button type="submit" class="btn btn-primary btn-flat" name="add"><i class="fa fa-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
                <form class="form-horizontal" method="POST" action="users_edit.php" enctype="multipart/form-data">
                    <input type="hidden" class="edituserid" name="id">

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
                    <div class="form-group">
                        <label for="edit_photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" id="edit_photo" name="photo">
                        </div>
                    </div>

                    <!-- Section Adresses -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Adresses</label>
                        <div class="col-sm-9">
                            <div id="edit-addresses-container">
                            <input type="hidden" name="address_ids" id="address_ids" value="">

                                <!-- Les adresses seront ajoutées ici dynamiquement -->
                            </div>
                            <button type="button" class="btn btn-info btn-sm btn-add-address" id="edit-add-address">
                                <i class="fa fa-plus"></i> Ajouter une adresse
                            </button>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Fermer</button>
                        <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i> Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Deleting...</b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="users_delete.php">
                <input type="hidden" class="userid" name="id">
                <div class="text-center">
                    <p>DELETE USER</p>
                    <h2 class="bold fullname"></h2>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="delete"><i class="fa fa-trash"></i> Delete</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Photo -->
<div class="modal fade" id="edit_photo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b><span class="fullname"></span></b></h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="users_photo.php" enctype="multipart/form-data">
                <input type="hidden" class="userid" name="id">
                <div class="form-group">
                    <label for="photo" class="col-sm-3 control-label">Photo</label>

                    <div class="col-sm-9">
                      <input type="file" id="photo" name="photo" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="upload"><i class="fa fa-check-square-o"></i> Update</button>
              </form>
            </div>
        </div>
    </div>
</div> 
