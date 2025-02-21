<style>
.modal-content {
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

.modal-header {
    background: #f8f9fa;
    border-radius: 12px 12px 0 0;
    padding: 20px;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
    padding: 15px;
}

.form-control {
    border-radius: 6px;
    border: 1px solid #ddd;
    padding: 8px 12px;
    height: auto;
    margin-bottom: 10px;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52,152,219,0.2);
}

.btn {
    border-radius: 6px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-success {
    background: #2ecc71;
    border: none;
}

.btn-success:hover {
    background: #27ae60;
    transform: translateY(-1px);
}
</style>

<!-- Transaction History -->
<div class="modal fade" id="transaction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              <h4 class="modal-title"><b>Transaction Full Details</b></h4>
            </div>
            <div class="modal-body">
              <p>
                Date: <span id="date"></span>
                <span class="pull-right">Transaction#: <span id="transid"></span></span> 
              </p>
              <p>
                Delivery Method: <span id="delivery"></span>
                
                </p>
                <p>Status:<span id="status"></span></p>
                <p>
                 Adresse: <span id="address"></span>
                </p>
                               
              <table class="table table-bordered">
                <thead>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                </thead>
                <tbody id="detail">
                    <tr id="dfee"style="display: none">
                        <td colspan="3" style="align:right">Delivery Fee:</td>
                        <td><span id="fee"></span></td>
                    </tr>
                  <tr>
                    <td colspan="3" style="aligh:right"><b>Total</b></td>
                    <td><span id="total"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left close" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile -->
<div class="modal fade" id="edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Update Profile</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="edit-form" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="firstname" class="col-sm-3 control-label">Firstname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-sm-3 control-label">Lastname</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="password" name="password" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact" class="col-sm-3 control-label">Contact Info</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $user['contact_info']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="photo" class="col-sm-3 control-label">Photo</label>
                        <div class="col-sm-9">
                            <input type="file" id="photo" name="photo">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>