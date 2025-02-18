<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales History
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales</li>
      </ol>
    </section>

    <!-- Main content -->
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
                        <span class="glyphicon glyphicon-print"></span> Print
                    </button>
                </form>

              </div>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th class="hidden"></th>
                  <th>Date</th>
                  <th>Buyer Name</th>
                  <th>Transaction#</th>
                  <th>Status</th>
                  <th>Amount</th>
                  <th>Full Details</th>
                </thead>
                <tbody>
                  <?php
                    $conn = $pdo->open();

                    try{
                      $stmt = $conn->prepare("SELECT u.firstname,u.lastname,s.status,s.total,s.sales_date,s.id AS salesid FROM sales s LEFT JOIN users u ON u.id=s.user_id ORDER BY sales_date DESC");
                      $stmt->execute();
                      foreach($stmt as $row){
                        $total=0;
                        $total = $row['total'];
                        $stmt = $conn->prepare("SELECT * FROM details LEFT JOIN edition on edition.id=details.product_id WHERE details.sales_id=:id");
                        $stmt->execute(['id'=>$row['salesid']]);
                        
                        foreach($stmt as $details){
                          $subtotal = $details['price']*$details['quantity'];
                          #$total +=$subtotal;
                        }
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('M d, Y', strtotime($row['sales_date']))."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".$row['salesid']."</td>
                         
                               <td>
                              <input type='checkbox' class='status-toggle' data-id='".$row['salesid']."' ".($row['status'] ? 'checked' : '')." data-toggle='toggle' data-on='Active' data-off='Inactive' data-onstyle='success' data-offstyle='danger' data-size='small'>
                            </td>
                            <td>&#36; ".number_format($total, 2)."</td>
                            <td><button type='button' class='btn btn-info btn-sm btn-flat transact' data-id='".$row['salesid']."'><i class='fa fa-search'></i> View</button></td>
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
<!-- Date Picker -->
<script>
$(function(){
  //Date picker
  $('#datepicker_add').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })
  $('#datepicker_edit').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  })

  //Timepicker
  $('.timepicker').timepicker({
    showInputs: false
  })

  //Date range picker
  $('#reservation').daterangepicker()
  //Date range picker with time picker
  $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A' })
  //Date range as a button
  $('#daterange-btn').daterangepicker(
    {
      ranges   : {
        'Today'       : [moment(), moment()],
        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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

  $("#transaction").on("hidden.bs.modal", function () {
        $('.prepend_items').remove();
        $('#dfee').hide();
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
          var message = status ? 'Sale confirmed successfully' : 'Sale cancelled successfully';
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

  

</script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</body>
</html>
