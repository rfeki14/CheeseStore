<?php include 'includes/session.php'; ?>
<?php
$where = '';
$catid = 0;
if (isset($_GET['category'])) {
  $catid = intval($_GET['category']);
  if ($catid == -1) {
    $where = 'WHERE products.category_id IS NULL';
  } else {
    $where = 'WHERE products.category_id =' . $catid;
  }
}
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contient le contenu de la page -->
  <div class="content-wrapper">
    <!-- Content Header (En-tête de la page) -->
    <section class="content-header">
      <h1>
        Liste des Produits
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li>Produits</li>
        <li class="active">Liste des Produits</li>
      </ol>
    </section>

    <!-- Main content -->
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
              <a href="#addnew" data-toggle="modal" class="btn btn-success btn-sm rounded-pill" id="addproduct">
                <i class="fa fa-plus"></i> Nouveau Produit
              </a>
              <div class="pull-right">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Catégorie: </label>
                    <select class="form-control input-sm" id="select_category">
                    <option value="0" <?php echo ($catid == 0) ? 'selected' : ''; ?>>TOUS</option>
                    <option value="-1" <?php echo ($catid == -1) ? 'selected' : ''; ?>>Aucune</option>
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
                  <th>Catégorie</th>
                  <th>Photo</th>
                  <th>Description</th>
                  <th>Quantité</th>
                  <th>Vues Aujourd'hui</th>
                  <th>Actions</th>
                </thead>
                <tbody>
                  <?php
                  $conn = $pdo->open();

                 
                    try {
                      $now = date('Y-m-d');
                      $stmt = $conn->prepare("SELECT products.*, category.name AS catname FROM products LEFT JOIN category ON category.id=products.category_id $where");
                      $stmt->execute();
                      foreach ($stmt as $row) {
                        $image = (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/noimage.jpg';
                        $counter = ($row['date_view'] == $now) ? $row['counter'] : 0;
                        $category = (!empty($row['catname'])) ? htmlspecialchars($row['catname']) : 'Aucune';
                        echo "
                            <tr>
                              <td>" . htmlspecialchars($row['name']) . "</td>
                              <td>" . $category . "</td>
                              <td>
                                <img src='" . htmlspecialchars($image) . "' height='30px' width='30px'>
                                <span class='pull-right'><a href='#edit_photo' class='photo' data-toggle='modal' data-id='" . $row['id'] . "'><i class='fa fa-edit'></i></a></span>
                              </td>
                              <td>
                                <a href='#description' data-toggle='modal' class='btn btn-info btn-sm rounded-pill desc' data-id='" . $row['id'] . "'>
                                  <i class='fa fa-search'></i> Voir
                                </a>
                              </td>
                              <td> " . number_format($row['qtty'], 3) . '</td>
                              <td>' . $counter . "</td>
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
    <?php include 'includes/products_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<script>
$(document).ready(function() {
    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
    });

    $(document).on('click', '.delete', function(e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
    });

    $(document).on('click', '.photo', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
    });

    $(document).on('click', '.desc', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
    });

    $('#select_category').change(function() {
        var val = $(this).val();
        if (val == 0) {
            window.location = 'products.php';
        } else if (val == -1) {
            window.location = 'products.php?category=-1';
        } else {
            window.location = 'products.php?category=' + val;
        }
    });

    $('#addproduct').click(function(e) {
        e.preventDefault();
        getCategory();
    });

    $("#addnew").on("hidden.bs.modal", function() {
        $('.append_items').remove();
        $('#category').html("")
    });

    $("#edit").on("hidden.bs.modal", function() {
        $('.append_items').remove();
        $('#edit_category').html("")
    });
});

function getCategory(id) {
  console.log(id);
    $.ajax({
        type: 'POST',
        url: 'category_fetch.php',
        data: {},
        dataType: 'json',
        success: function(response) {
          if (id){
            var select = document.getElementById("edit_category");
            response.forEach(category => {
                var option = document.createElement("option");
                option.text = category.name;
                option.value = category.id;
                select.appendChild(option);
                if(category.id==id){
                  $('select option[value='+id+']').attr("selected",true);
                  console.log("selected");
                }
            });}
            else{
              console.log("no id");
              var select = document.getElementById("category");
              response.forEach(category => {
                var option = document.createElement("option");
                option.text = category.name;
                option.value = category.id;
                select.appendChild(option);
            });
        }
      },
        error: function(xhr, status, error) {
            console.error("Erreur AJAX:", error);
        }
    });
}

function getRow(id) {
    $.ajax({
        type: 'POST',
        url: 'products_row.php',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            if (response) {
                populateDescriptionModal(response); 
                $('.prodid').val(response.prodid);
                $('.name').html("ID: " + response.prodid + " - " + response.prodname);
                $('#edit_name').val(response.prodname);
                $('#edit_quantity').val(response.qtty);
                getCategory(response.category_id);

                if (response.photo && response.photo !== "../images/") {
                    $('#old_photo_preview').attr('src', response.photo).show();
                } else {
                    $('#old_photo_preview').attr('src', '../images/noimage.jpg').show();
                }

                

                if (CKEDITOR.instances["edit_description"]) {
                    CKEDITOR.instances["edit_description"].setData(response.description);
                } else {
                    CKEDITOR.replace('edit_description');
                    CKEDITOR.instances["edit_description"].setData(response.description);
                }
            } else {
                console.error("Réponse invalide:", response);
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur AJAX:", error);
        }
    });
}
</script>