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
                      $stmt = $conn->prepare("SELECT *, sales.id AS salesid FROM sales LEFT JOIN users ON users.id=sales.user_id ORDER BY sales_date DESC");
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
    <?php include '../includes/profile_modal.php'; ?>

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
 
  $(function() {
    $(document).ready(function() {
    $('.status-toggle').each(function() {
        var isChecked = $(this).attr('checked') ? true : false;
        $(this).bootstrapToggle(isChecked ? 'on' : 'off');
    });
});
  $(document).on('change', '.status-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $toggle = $(this);
    var id = $toggle.data('id');
    var newStatus = $toggle.prop('checked') ? 1 : 0; // sale's intended status
    var prevStatus = newStatus ? 0 : 1; // Previous status before change

    // Disable toggle to prevent multiple clicks & show loading
    $toggle.bootstrapToggle('disable');
    $toggle.closest('.toggle-container').append('<span class="spinner-border spinner-border-sm ml-2"></span>');

    $.ajax({
      url: 'sale_status.php',
      type: 'POST',
      data: { id: id, status: newStatus },
      dataType: 'json', // Expecting JSON response
      success: function(response) {
        $('.spinner-border').remove();
        $toggle.bootstrapToggle('enable');

        // Ensure the response has a valid status
        if (response && response.status === 'ok' && typeof response.newStatus !== 'undefined') {
          var message = response.newStatus ? 'Sale Confirmed successfully' : 'Sale Cancelled successfully';
          $(".content").prepend(
            '<div class="alert alert-success alert-dismissible fade show">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
            '<i class="icon fa fa-check"></i> ' + message +
            '</div>'
          );

          // Auto-dismiss after 2s
          setTimeout(function() {
            $('.alert').fadeOut('slow', function() { $(this).remove(); });
          }, 2000);
        } else {
          // If the server rejects the update, reset toggle
          $toggle.bootstrapToggle('toggle');
        }
      },
      error: function() {
        $('.spinner-border').remove();
        $toggle.bootstrapToggle('enable');
        
        // Reset toggle to previous state
        $toggle.bootstrapToggle(prevStatus ? 'on' : 'off');
      }
    });

    return false;
  });


  $(document).on('click', '.transact', function(e) {
    e.preventDefault();
    $('#transaction').modal('show');
    var id = $(this).data('id');
    console.log("click");

    $.ajax({
        type: 'POST',
        url: 'transact.php',
        data: { id: id },
        dataType: 'json',
        success: function(response) {
            // Since jQuery automatically parses the JSON response, no need for JSON.parse
            console.log(response);

            // Populate modal with the response data
            $('#date').html(response.date);
            $('#transid').html(response.transaction);
            $('#delivery').html(response.delivery_method);
            $('#status').html(response.status);
            $('#address').html(response.address);
            $('#detail').prepend(response.list);
            if(response.fee!=0){
              console.log('fee');
              $('#dfee').show();
              $('#fee').html(response.fee);
            }
            $('#total').html(response.total);
        },
        error: function(xhr, status, error) {
            // Handle any AJAX errors here
            console.log("Error:", error);
        }
    });
});

  $("#transaction").on("hidden.bs.modal", function () {
        $('.prepend_items').remove();
        $('#dfee').hide();
    });


</script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
</body>
</html>
