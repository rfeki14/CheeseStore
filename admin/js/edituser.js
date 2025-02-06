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

                let container = $('#addresses-container');
                container.empty();
                
                addresses.forEach(address => {
                    container.append(createAddressSpan(address));
                });
            }
        });
    }

    function createAddressSpan(address) {
        return `
            <span class="address-item">
                ${address.street}, ${address.city}, ${address.state} ${address.zip_code}, ${address.country}
                <button type="button" class="btn btn-danger btn-sm remove-address" data-id="${address.id}">Supprimer</button>
            </span>
        `;
    }

    $('#edit').on('show.bs.modal', function(event){
        let button = $(event.relatedTarget);
        let userId = button.data('id');
        console.log('User ID:', userId); // Add this line for debugging
        $('.userid').val(userId); // Ensure the user ID is set in the hidden input field
        loadAddresses(userId);
    });

    $('#add-address').click(function(){
        $('#addresses-container').append(createAddressHTML());
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
                    } else {
                        alert('Erreur lors de la suppression de l\'adresse');
                    }
                }
            });
        } else {
            $(this).closest('.address-item').remove();
        }
    });

    // Address form submission
    $('#addressForm').on('submit', function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: 'add_address.php',
            data: formData,
            success: function(response){
                if(response == 'success'){
                    alert('Adresses mises à jour avec succès');
                    $('#edit').modal('hide');
                    location.reload(); // Reload the page to show the updated addresses
                } else {
                    alert('Erreur lors de la mise à jour des adresses');
                }
            }
        });
    });
});