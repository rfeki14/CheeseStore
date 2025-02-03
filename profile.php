<?php include 'includes/session.php'; ?>
<?php
	if(!isset($_SESSION['user'])){
		header('location: index.php');
	}
?>
<?php include 'includes/header.php'; ?>
<style>
.profile-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.profile-header {
    padding: 30px;
    display: flex;
    align-items: start;
}

.profile-image {
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    width: 150px;
    height: 150px;
    object-fit: cover;
}

.profile-info {
    padding-left: 30px;
}

.profile-info h4 {
    margin-bottom: 15px;
    color: #333;
}

.transaction-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.transaction-header {
    border-bottom: 2px solid #f4f4f4;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

#example1 {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    margin-bottom: 20px;
}

#example1 thead th {
    background: #f8f9fa;
    padding: 12px;
    color: #444;
}

#example1 tbody tr {
    background: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

#example1 tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#example1 td {
    padding: 12px;
    vertical-align: middle;
}

.btn-info.transact {
    background: #3498db;
    border: none;
    padding: 8px 15px;
    transition: all 0.3s;
}

.btn-info.transact:hover {
    background: #2980b9;
    transform: translateY(-1px);
}

/* Styles de pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination > li > a,
.pagination > li > span {
    border: none;
    color: #555;
    margin: 0 5px;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    line-height: 40px;
    text-align: center;
    padding: 0;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.pagination > li > a:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
}

.pagination > .active > a,
.pagination > .active > span {
    background-color: #3498db !important;
    color: white;
    border: none;
}

/* Amélioration du tableau */
#example1_wrapper .row:first-child {
    margin-bottom: 20px;
}

#example1_filter input {
    border-radius: 20px;
    border: 1px solid #ddd;
    padding: 5px 15px;
    margin-left: 10px;
}

#example1_length select {
    border-radius: 20px;
    border: 1px solid #ddd;
    padding: 5px 30px 5px 15px;
}
</style>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='callout callout-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}

	        			if(isset($_SESSION['success'])){
	        				echo "
	        					<div class='callout callout-success'>
	        						".$_SESSION['success']."
	        					</div>
	        				";
	        				unset($_SESSION['success']);
	        			}
	        		?>
	        		<div class="box box-solid profile-card">
	        			<div class="box-body profile-header">
	        				<div class="profile-image-container">
	        					<img src="<?php echo (!empty($user['photo'])) ? 'images/'.$user['photo'] : 'images/profile.jpg'; ?>" class="profile-image">
	        				</div>
	        				<div class="col-sm-9 profile-info">
	        					<div class="row">
	        						<div class="col-sm-3">
	        							<h4>Name:</h4>
	        							<h4>Email:</h4>
	        							<h4>Contact Info:</h4>
	        							<h4>Address:</h4>
	        							<h4>Member Since:</h4>
	        						</div>
	        						<div class="col-sm-9">
	        							<h4><?php echo $user['firstname'].' '.$user['lastname']; ?>
	        								<span class="pull-right">
	        									<button class="btn btn-success btn-flat btn-sm" onclick="$('#edit').modal('show');"><i class="fa fa-edit"></i> Edit</button>
	        								</span>
	        							</h4>
	        							<h4><?php echo $user['email']; ?></h4>
	        							<h4><?php echo (!empty($user['contact_info'])) ? $user['contact_info'] : 'N/a'; ?></h4>
	        							<h4><?php echo (!empty($user['address'])) ? $user['address'] : 'N/a'; ?></h4>
	        							<h4><?php echo date('M d, Y', strtotime($user['created_on'])); ?></h4>
	        						</div>
	        					</div>
	        				</div>
	        			</div>
	        		</div>
	        		<div class="box box-solid transaction-card">
	        			<div class="transaction-header">
	        				<h4 class="box-title"><i class="fa fa-calendar"></i> <b>Transaction History</b></h4>
	        			</div>
	        			<div class="box-body">
	        				<table class="table table-bordered" id="example1">
	        					<thead>
	        						<th class="hidden"></th>
	        						<th>Date</th>
	        						<th>Transaction#</th>
	        						<th>Amount</th>
	        						<th>Full Details</th>
	        					</thead>
	        					<tbody>
	        					<?php
	        						$conn = $pdo->open();

	        						try{
	        							$stmt = $conn->prepare("SELECT * FROM sales WHERE user_id=:user_id ORDER BY sales_date DESC");
	        							$stmt->execute(['user_id'=>$user['id']]);
	        							foreach($stmt as $row){
	        								$stmt2 = $conn->prepare("SELECT * FROM details LEFT JOIN products ON products.id=details.product_id WHERE sales_id=:id");
	        								$stmt2->execute(['id'=>$row['id']]);
	        								$total = 0;
	        								foreach($stmt2 as $row2){
	        									$subtotal = $row2['price']*$row2['quantity'];
	        									$total += $subtotal;
	        								}
	        								echo "
	        									<tr>
	        										<td class='hidden'></td>
	        										<td>".date('M d, Y', strtotime($row['sales_date']))."</td>
	        										<td>".$row['id']."</td>
	        										<td>&#36; ".number_format($total, 2)."</td>
	        										<td><button class='btn btn-sm btn-flat btn-info transact' data-id='".$row['id']."'><i class='fa fa-search'></i> View</button></td>
	        									</tr>
	        								";
	        							}

	        						}
        							catch(PDOException $e){
										echo "There is some problem in connection: " . $e->getMessage();
									}

	        						$pdo->close();
	        					?>
	        					</tbody>
	        				</table>
	        			</div>
	        		</div>
	        	</div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>
  
  	<?php include 'includes/footer.php'; ?>
  	<?php include 'includes/profile_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    // Gestionnaire unique pour le formulaire Edit
    $('#edit-form').submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            type: 'POST',
            url: 'profile_edit.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                console.log('Response:', response);
                if(response.trim() === 'success'){
                    location.reload();
                } else {
                    alert('Error: ' + response);
                }
            }
        });
    });

    // Gestionnaire des boutons Close
    $('.modal .close, .modal .btn-default').click(function(){
        $(this).closest('.modal').modal('hide');
    });

    // Reste du code pour les transactions
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
                $('#detail').prepend(response.list);
                $('#total').html(response.total);
            }
        });
    });

    $("#transaction").on("hidden.bs.modal", function () {
        $('.prepend_items').remove();
    });

    // Supprimer tous les autres gestionnaires de formulaire et garder uniquement celui-ci
    $('#edit-form').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'profile_edit.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                console.log('Server response:', response); // Debug
                if(response.trim() == 'success'){
                    alert('Profile updated successfully');
                    $('#edit').modal('hide');
                    window.location.reload();
                } else {
                    alert('Error updating profile: ' + response);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
        return false;
    });

    // Gestionnaire pour les transactions
    // ...existing code...
});
</script>
</body>
</html>