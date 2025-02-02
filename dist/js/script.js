// ...existing code...

$(document).on('click', '.addcart', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $.ajax({
        type: 'POST',
        url: 'cart_add.php',
        data: {id:id},
        dataType: 'json',
        success: function(response){
            if(!response.error){
                getTotal();
                getCart();
                showAlert('success', 'Product added to cart');
            }
            else {
                showAlert('error', response.message);
            }
        }
    });
});

function showAlert(type, message) {
    const alertDiv = $('<div>')
        .addClass('alert')
        .addClass(type === 'success' ? 'alert-success' : 'alert-danger')
        .text(message)
        .fadeIn();
    
    $('.content').prepend(alertDiv);
    
    setTimeout(() => {
        alertDiv.fadeOut(() => alertDiv.remove());
    }, 3000);
}

// ...existing code...
