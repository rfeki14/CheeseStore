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
              <a href="#addnew" data-toggle="modal" class="btn btn-success btn-sm rounded-pill">
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
                      $stmt = $conn->prepare("SELECT * FROM users WHERE type=:type");
                      $stmt->execute(['type'=>0]);
                      foreach($stmt as $row){
                        $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                        echo "
                          <tr>
                            <td>
                              <img src='".$image."' height='30px' width='30px'>
                              <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='".$row['id']."'><i class='fa fa-edit'></i></a></span>
                            </td>
                            <td>".$row['email']."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".$row['address']."</td>
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
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script>
$(document).ready(function(){
  // Initialiser les toggles
  $('.status-toggle').bootstrapToggle();
  
  // Existant event handlers
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

  // ...existing code...
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
      $('#edit_address').val(response.address);
      $('#edit_contact').val(response.contact_info);
      $('.fullname').html(response.firstname+' '+response.lastname);
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
