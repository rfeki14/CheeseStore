$(function() {
    $(document).on('click', '.addcart', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var price = $(this).data('price');
        var name = $(this).data('name');
        
        $.ajax({
            type: 'POST',
            url: 'cart_add.php',
            data: {
                id: id,
                quantity: 1
            },
            dataType: 'json',
            success: function(response) {
                if (!response.error) {
                    // Update cart count
                    getCart();
                    // Show success message
                    Swal.fire({
                        title: 'Success!',
                        text: name + ' added to cart',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });

    function getCart() {
        $.ajax({
            type: 'POST',
            url: 'cart_fetch.php',
            dataType: 'json',
            success: function(response) {
                $('#cart_menu').html(response.list);
                $('.cart_count').html(response.count);
                updateCartCount(response.count);
            }
        });
    }

    function updateCartCount(count) {
        document.getElementById('cart-count').textContent = count;
    }
});
