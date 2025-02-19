<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<!-- Déplacer les styles ici -->
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<!-- Ajouter ces styles dans la section head -->
<style>
.modal-backdrop {
    display: none !important;
}
.modal {
    background: rgba(0,0,0,0.5);
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#adduserModal" data-toggle="modal" class="btn btn-success btn-sm rounded-pill">
                <i class="fa fa-plus"></i> New User
              </a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Photo</th>
                  <th>Email</th>
                  <th>Name</th>
                  <th>Address</th>
                  <th>Phone Number</th>
                  <th>Status</th>
                  <th>Date Added</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("
                          SELECT users.*, 
                                 GROUP_CONCAT(
                                     CONCAT(address.street, ', ', address.city, ', ', 
                                           address.state, ' ', address.zip_code, ', ', address.country)
                                     SEPARATOR '<br>'
                                 ) as addresses
                          FROM users 
                          LEFT JOIN user_addresses ON users.id = user_addresses.user_id
                          LEFT JOIN address ON user_addresses.address_id = address.id
                          WHERE users.type = :type
                          GROUP BY users.id
                      ");
                      $stmt->execute(['type'=>0]);
                      foreach($stmt as $row){
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        $addresses = !empty($row['addresses']) ? $row['addresses'] : 'No address registered';
                        echo "
                          <tr>
                            <td>
                              <img src='".$image."' height='30px' width='30px'>
                              <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'><i class='fa fa-edit'></i></a></span>
                            </td>
                            <td>".$row['email']."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".html_entity_decode($addresses)."</td>
                            <td>".$row['contact_info']."</td>
                            <td>
                              <input type='checkbox' class='status-toggle' data-id='".$row['id']."' ".($row['status'] ? 'checked' : '')." data-toggle='toggle' data-on='Active' data-off='Inactive' data-onstyle='success' data-offstyle='danger' data-size='small'>
                            </td>
                            <td>".date('M d, Y', strtotime($row['created_on']))."</td>
                            <td>
                              <div class='btn-group'>
                                <a href='cart.php?user=".$row['id']."' class='btn btn-info btn-sm rounded-pill' title='View Cart'>
                                  <i class='fa fa-shopping-cart'></i> Cart
                                </a>
                                <button class='btn btn-primary btn-sm edit rounded-pill' data-id='".$row['id']."' title='Edit'>
                                  <i class='fa fa-edit'></i> Edit
                                </button>
                                <button class='btn btn-danger btn-sm delete rounded-pill ms-2' data-id='".$row['id']."' title='Delete'>
                                  <i class='fa fa-trash'></i> Delete
                                </button>
                              </div>
                            </td>
                          </tr>
                        ";
                      }
                    }
                    catch(PDOException $e){
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
    <?php include 'includes/users_modal.php'; ?>

</div>
<!-- ./wrapper -->

<!-- Modifier l'ordre des scripts -->
<?php include 'includes/scripts.php'; ?>
<!-- <script src="js/edituser.js"></script>-->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
$(document).ready(function(){

  
  function loadAddresses(userId) {
        console.log('Loading addresses for user ID:', userId); // Add this line for debugging
        $.ajax({
            url: 'get_addresses.php',
            type: 'GET',
            data: {id: userId},
            success: function(response) {
                let addresses;
                try {
                    addresses = JSON.parse(response);
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    addresses = [];
                }

                if (!Array.isArray(addresses)) {
                    console.error('Expected an array but got:', addresses);
                    addresses = [];
                }

                let container = $('#edit-addresses-container');
                container.empty();
                
                addresses.forEach(address => {
                    container.append(createAddressSpan(address));
                });
            },
            error: function(){
              console.log("something went wrong")
            }
        });
    }
function createAddressHTML(address = {}) {
    return `<form id="addForm" class="form-horizontal">
        <div class="address-item">
            <input type="hidden" id="id" name="id" value="${address.id || ''}">
            <div class="form-group">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="tel" value="${address.phone || ''}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="street" name="street" placeholder="Rue" value="${address.street || ''}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="${address.city || ''}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="state" name="state" placeholder="État/Région" value="${address.state || ''}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Code postal" value="${address.zip_code || ''}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="country" name="country" placeholder="Pays" value="${address.country || ''}" required>
            </div>
            <button type="button" class="btn btn-primary btn-sm add-add"><i class="fa fa-check"></i> Confirmer les adresses</button>
            <button type="button" class=" pull-right btn btn-danger btn-sm remove-address">Supprimer</button>
        </div>
        </form>
    `;
}

function updateAddressIds() {
    var ids = [];
    // For each remove button in the addresses container, retrieve its data-id
    $('#addresses-container .remove-address').each(function(){
        var id = $(this).data('id');
        if(id) {
            ids.push(id);
        }
    });
    // Update the hidden input with a comma-separated list of IDs
    $('#address_ids').val(ids.join(','));
}


function createAddressSpan(address) {
    return `<p>
        <span class="address-item">
            ${address.street}, ${address.city}, ${address.state} ${address.zip_code}, ${address.country}
            <button type="button" class="pull-right btn btn-danger btn-sm remove-address" data-id="${address.id}">Supprimer</button>
        </span><br></p>
    `;
}

    


    $('#edit').on('show.bs.modal', function(event){
        var userId = $(this).data('id');
        console.log('User ID:', userId); // Add this line for debugging
        if (userId) {
            $('.userid').val(userId); // Ensure the user ID is set in the hidden input field
            loadAddresses(userId);
        } else {
            console.error('User ID not found');
        }
    });

    $('#add-address').click(function(){
        $('#addresses-container').append(createAddressHTML());
    });

    $('edit-add-address').click(function(){
      $('edit-addresses-container').append(createAddressHTML());
    });

    $(document).on('click', '.remove-address', function(){
        var addressId = $(this).data('id');
        if (addressId) {
            $.ajax({
                url: 'delete_address.php',
                type: 'POST',
                data: {id: addressId},
                success: function(response) {
                    if (response == 'success') {
                        alert('Adresse supprimée avec succès');
                        loadAddresses($('.userid').val());
                        updateAddressIds();
                    } else {
                        alert('Erreur lors de la suppression de l\'adresse');
                    }
                }
            });
        } else {
            $(this).closest('.address-item').remove();
        }
    });
    $(document).on('click', '.add-add', function(){
        //collecting data
        let userid = $('#edituserid').val();
        let street = $('#street').val();
        let city = $('#city').val();
        let state = $('#state').val();
        let zip_code = $('#zipcode').val();
        let country = $('#country').val();
        let phone = $('#phone').val();
        console.log(userid, street, city, state, zip_code, country); // Add this line for debugging
            $.ajax({
            type: 'POST',
            url: 'add_address.php',
            data: {
                user_id: userid,
                street: street,
                city: city,
                state: state,
                zip_code: zip_code,
                country: country,
                phone : phone
            },
            dataType: 'json',
            success: function(response){
                console.log('Response:', response); // Add this line for debugging
                if(response.status){
                    alert('Adresse ajouté avec succès');
                    let form = $('#addForm');
                    form.remove();
                    let container = $('#addresses-container');
                    container.append(createAddressSpan(response.address))
                    updateAddressIds();
                } else {
                    alert('Erreur lors de la mise à jour des adresses');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
        
    });


  // Initialiser les toggles
  $('.status-toggle').bootstrapToggle();
  
  // Existant event handlers
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    console.log(id)
    getRow(id);
    loadAddresses(id);
    
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.status', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

  // Nouveau gestionnaire de statut corrigé
  $(document).on('change', '.status-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $toggle = $(this);
    var id = $toggle.data('id');
    var status = $toggle.prop('checked');
    
    // Empêcher les clics multiples
    $toggle.bootstrapToggle('disable');
    
    $.ajax({
      url: 'users_status.php',
      type: 'POST',
      data: {
        id: id,
        status: status ? 1 : 0
      },
      success: function(response) {
        $toggle.bootstrapToggle('enable');
        if(response == 'ok') {
          // Message plus discret en haut de la page
          var message = status ? 'User activated successfully' : 'User deactivated successfully';
          $(".content").prepend(
            '<div class="alert alert-success alert-dismissible">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<h4><i class="icon fa fa-check"></i> Success!</h4>' + message +
            '</div>'
          );
          
          // Auto-dismiss après 2 secondes
          setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
              $(this).remove();
            });
          }, 2000);
        } else {
          // En cas d'erreur, revenir à l'état précédent
          $toggle.bootstrapToggle('toggle');
        }
      },
      error: function() {
        // En cas d'erreur, revenir à l'état précédent
        $toggle.bootstrapToggle('enable');
        $toggle.bootstrapToggle('toggle');
      }
    });

    return false;
  });

  
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'users_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.userid').val(response.id);
      $('#edit_email').val(response.email);
      $('#edit_password').val(response.password);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_contact').val(response.contact_info);
      $('.fullname').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>
<style>
.btn {
    padding: 4px 12px;  // Reduced from 8px 16px
    font-weight: 400;   // Reduced from 500
    transition: all 0.3s ease;
    font-size: 12px;    // Added smaller font size
}

.btn i {
    font-size: 11px;    // Added smaller icon size
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);  // Reduced shadow
}

.btn-group {
    gap: 4px;  // Reduced from 8px
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
