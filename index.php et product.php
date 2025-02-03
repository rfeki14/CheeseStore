success: function(response){
    if(!response.error){
        // Mise à jour du compteur du panier
        updateCartCount(response.count);
        alert(response.message);
    }
    else {
        alert(response.message);
    }
}
