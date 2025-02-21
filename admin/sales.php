<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Conteneur de contenu. Contient le contenu de la page -->
  <div class="content-wrapper">
    <!-- En-tête de contenu (en-tête de page) -->
    <section class="content-header">
      <h1>
        Historique des Ventes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li class="active">Ventes</li>
      </ol>
    </section>

    <!-- Contenu principal -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <div class="pull-right">
              <form method="POST" class="form-inline" action="sales_print.php">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right col-sm-8" id="reservation" name="date_range" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm btn-flat" name="print">
                        <span class="glyphicon glyphicon-print"></span> Imprimer
                    </button>
                </form>

              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Date</th>
                  <th>Nom de l’Acheteur</th>
                  <th>Transaction#</th>
                  <th>Statut</th>
                  <th>Montant</th>
                  <th>Détails Complets</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT u.firstname, u.lastname, s.status, s.total, s.sales_date, s.id AS salesid FROM sales s LEFT JOIN users u ON u.id=s.user_id ORDER BY sales_date DESC");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $total = 0;
                        $total = $row['total'];
                        $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN edition ON edition.id=details.product_id WHERE details.sales_id=:id");
                        $stmt->execute(['id'=>$row['salesid']]);
                        
                        foreach($stmt as $details){
                          $subtotal = $details['price'] * $details['quantity'];
                          #$total += $subtotal;
                        }
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('d M, Y', strtotime($row['sales_date']))."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".$row['salesid']."</td>
                            <td>
                              <input type='checkbox' class='status-toggle' data-id='".$row['salesid']."' ".($row['status'] ? 'checked' : '')." data-toggle='toggle' data-on='Confirmé' data-off='Non Confirmé' data-onstyle='success' data-offstyle='danger' data-size='small'>
                            </td>
                            <td>&#36; ".number_format($total, 2)."</td>
                            <td><button type='button' class='btn btn-info btn-sm btn-flat transact' data-id='".$row['salesid']."'><i class='fa fa-search'></i> Voir</button></td>
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
    <?php include 'includes/transaction_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
<!-- Sélecteur de Date -->
<script>
$(function(){
  // Sélecteur de date
  $('#datepicker_add').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
 })
  $('#datepicker_edit').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })

  // Sélecteur de temps
  $('.timepicker').timepicker({
    showInputs: false
  })

  // Sélecteur de plage de dates
  $('#reservation').daterangepicker()
  // Sélecteur de plage de dates avec sélecteur de temps
  $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
  // Plage de dates en tant que bouton
  $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Aujourd\'hui'       : [moment(), moment()],
        'Hier'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Derniers 7 Jours' : [moment().subtract(6, 'days'), moment()],
        'Derniers 30 Jours': [moment().subtract(29, 'days'), moment()],
        'Ce Mois'  : [moment().startOf('month'), moment().endOf('month')],
        'Dernier Mois'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment().subtract(29, 'days'),
      endDate  : moment()
    },
    function (start, end) {
      $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
    }
  )
  
});
</script>
<script>
 $(document).ready(function() {
    $('.status-toggle').bootstrapToggle();
});
  $(function() {

  $(document).on('click', '.transact', function(e){
        e.preventDefault();
        $('#transaction').modal('show');
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: 'transaction.php',
            data: {id:id},
            dataType: 'json',
            success:function(response){
                $('#date').html(response.date);
                $('#transid').html(response.transaction);
                $('#status').html(response.status);
                $('#delivery').html(response.delivery_method);
                $('#address').html(response.address);
                $('#detail').prepend(response.list);
                if(response.fee!=0){
                    $('#dfee').show();
                    $('#fee').html(response.fee);
                };
                $('#total').html(response.total);
            }
        });
    });

    $("#transaction").on("hidden.bs.modal", function () {
        $('.prepend_items').remove();
        $('#dfee').hide();
        $('#fee').html(0);
    });

  $(document).on('change', '.status-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $toggle = $(this);
    var id = $toggle.data('id');
    var status = $toggle.prop('checked');
    
    // Empêcher les clics multiples
    $toggle.bootstrapToggle('disable');
    console.log($toggle,status);
    $.ajax({
      url: 'sale_status.php',
      type: 'POST',
      data: {
        id: id,
        status: status ? 1 : 0
      },
      success: function(response) {
        $toggle.bootstrapToggle('enable');
        if(response == 'ok') {
          // Message plus discret en haut de la page
          var message = status ? 'Vente confirmée avec succès' : 'Vente annulée avec succès';
          $(".content").prepend(
            '<div class="alert alert-success alert-dismissible">' +
            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
            '<h4><i class="icon fa fa-check"></i> Succès!</h4>' + message +
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

</script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</body>
</html>