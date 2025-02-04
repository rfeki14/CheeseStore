<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    
    <div class="content-wrapper">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Shopping Cart</h1>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <div class="box box-solid">
                            <div class="box-body">
                                <table class="table table-bordered" id="cart_table">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total = 0;
                                        if(isset($_SESSION['user'])){
                                            $stmt = $conn->prepare("
                                                SELECT *, cart.quantity as cart_quantity, cart.price as cart_price 
                                                FROM cart 
                                                LEFT JOIN products ON products.id=cart.product_id 
                                                WHERE user_id=:user_id");
                                            $stmt->execute(['user_id'=>$user['id']]);
                                            foreach($stmt as $row){
                                                $image = !empty($row['photo']) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
                                                // Calcul du sous-total (prix par kg * quantité en kg)
                                                $subtotal = $row['cart_price'] * ($row['cart_quantity']/1000);
                                                $total += $subtotal;
                                                ?>
                                                <tr>
                                                    <td><img src='<?php echo $image ?>' width='50px' height='50px'></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php echo number_format($row['cart_price'], 2); ?> DT/kg</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control quantity" 
                                                                data-id="<?php echo $row['product_id']; ?>"
                                                                value="<?php echo $row['cart_quantity']; ?>"
                                                                min="100" max="5000" step="100"
                                                            >
                                                            <span class="input-group-text">g</span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo number_format($subtotal, 2); ?> DT</td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm cart_delete" data-id="<?php echo $row['product_id']; ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" align="right"><b>Total</b></td>
                                            <td><b><?php echo number_format($total, 2); ?> DT</b></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?php if($total > 0): ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button class="btn btn-success btn-lg btn-flat" id="checkout">
                                                <i class="fa fa-shopping-cart"></i> Checkout
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
    $(document).on('change', '.quantity', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var qty = $(this).val();
        
        $.ajax({
            type: 'POST',
            url: 'cart_update.php',
            data: {
                id: id,
                quantity: qty,
            },
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    location.reload();
                }
                else{
                    alert(response.message);
                }
            }
        });
    });

    $(document).on('click', '.cart_delete', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        console.log("test",id);
        
        $.ajax({
            type: 'POST',
            url: 'cart_delete.php',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response){
                if(!response.error){
                    alert(response.message);
                }
                else{
                    alert(response.message);
                }
            }
        });
    });

    $('#checkout').click(function(e){
        e.preventDefault();
        window.location.href = 'checkout.php';
    });
});
</script>
</body>
</html>