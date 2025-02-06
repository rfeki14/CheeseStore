
$(document).ready(function(){
    function loadAddresses(userId) {
        $.ajax({
            url: 'get_addresses.php',
            type: 'GET',
            data: {id: userId},
            success: function(response) {
                let addresses = JSON.parse(response);
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

    function createAddressHTML(address = {}) {
        return `
            <div class="address-item">
                <input type="hidden" name="addresses[][id]" value="${address.id || ''}">
                <div class="form-group">
                    <input type="text" class="form-control" name="addresses[][street]" placeholder="Rue" value="${address.street || ''}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="addresses[][city]" placeholder="Ville" value="${address.city || ''}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="addresses[][state]" placeholder="État/Région" value="${address.state || ''}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="addresses[][zip_code]" placeholder="Code postal" value="${address.zip_code || ''}" required>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="addresses[][country]" placeholder="Pays" value="${address.country || ''}" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-address">Supprimer</button>
            </div>
        `;
    }

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

    $('#edit').on('show.bs.modal', function(event){
        let button = $(event.relatedTarget);
        let userId = button.data('id');
        $('.userid').val(userId);
        loadAddresses(userId);
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
