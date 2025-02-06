function addNewAddressField() {
    const addressContainer = document.getElementById('addresses-container');
    const timestamp = new Date().getTime();
    const newAddressHtml = `
        <div class="address-block panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">Nouvelle adresse</h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Rue</label>
                            <input type="text" class="form-control" name="addresses[new_${timestamp}][street]" placeholder="Rue" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Ville</label>
                            <input type="text" class="form-control" name="addresses[new_${timestamp}][city]" placeholder="Ville" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Code postal</label>
                            <input type="text" class="form-control" name="addresses[new_${timestamp}][zip_code]" placeholder="Code postal">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>État/Région</label>
                            <input type="text" class="form-control" name="addresses[new_${timestamp}][state]" placeholder="État/Région">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pays</label>
                            <input type="text" class="form-control" name="addresses[new_${timestamp}][country]" placeholder="Pays" value="France">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-danger remove-address">
                        <i class="fa fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    `;
    addressContainer.insertAdjacentHTML('beforeend', newAddressHtml);
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-address').addEventListener('click', addNewAddressField);

    // Toggle readonly attribute for address fields
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('edit-address')) {
            e.preventDefault();
            const addressBlock = e.target.closest('.address-block');
            const inputs = addressBlock.querySelectorAll('input');
            inputs.forEach(function (input) {
                if (input.hasAttribute('readonly')) {
                    input.removeAttribute('readonly');
                } else {
                    input.setAttribute('readonly', 'readonly');
                }
            });
        }
    });

    // Gestion de la suppression d'adresse
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-address')) {
            e.preventDefault();
            const addressBlock = e.target.closest('.address-block');
            const addressId = addressBlock.querySelector('input[name$="[id]"]')?.value;

            if (addressId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')) {
                    fetch('delete_address.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'address_id=' + addressId
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                addressBlock.remove();
                            } else {
                                alert('Erreur lors de la suppression : ' + (data.error || 'Erreur inconnue'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la suppression de l\'adresse');
                        });
                }
            } else {
                // New address that hasn't been saved yet
                addressBlock.remove();
            }
        }
    });

    document.getElementById('change_password').addEventListener('click', function () {
        var passwordFields = document.getElementById('new_password_fields');
        if (passwordFields.style.display === 'none') {
            passwordFields.style.display = 'block';
        } else {
            passwordFields.style.display = 'none';
        }
    });

    // Password validation
    document.getElementById('profileForm').addEventListener('submit', function (e) {
        e.preventDefault();

        if (validateForm()) {
            const formData = new FormData(this);

            fetch('profile_edit.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        window.location.href = 'profile.php'; // Redirection directe vers profile.php
                    } else {
                        alert('Erreur: ' + data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la mise à jour du profil');
                });
        }
    });

    // Fonction de validation du formulaire
    function validateForm() {
        // Address validation
        const addressBlocks = document.querySelectorAll('.address-block');
        let hasAddressError = false;

        addressBlocks.forEach(block => {
            const street = block.querySelector('input[name$="[street]"]');
            const city = block.querySelector('input[name$="[city]"]');

            if (!street || !city) {
                console.error('Address input fields not found:', block);
                hasAddressError = true;
                return;
            }

            const streetValue = street.value.trim();
            const cityValue = city.value.trim();

            if (!streetValue || !cityValue) {
                hasAddressError = true;
                alert('La rue et la ville sont obligatoires pour chaque adresse.');
                street.focus();
                return false;
            }
        });

        if (hasAddressError) {
            return false;
        }

        // Password validation
        var passwordFields = document.getElementById('new_password_fields');
        if (passwordFields.style.display !== 'none') {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (newPass !== confirmPass) {
                alert('Les mots de passe ne correspondent pas');
                return false;
            }

            if (newPass.length < 6) {
                alert('Le nouveau mot de passe doit contenir au moins 6 caractères');
                return false;
            }
        }

        return true;
    }
});