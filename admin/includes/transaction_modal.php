<!-- Transaction History -->
<div class="modal fade" id="transaction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              <h4 class="modal-title"><b>Transaction Full Details</b></h4>
            </div>
            <div class="modal-body">
              <p>
                Date: <span id="date"></span>
                <span class="pull-right">Transaction#: <span id="transid"></span></span> 
              </p>
              <p>
              Méthode de livraison: <span id="delivery"></span>
                
                </p>
                <p>Statut:<span id="status"></span></p>
                <p>
                 Adresse: <span id="address"></span>
                </p>
                               
              <table class="table table-bordered">
                <thead>
                  <th>Produit</th>
                  <th>Prix</th>
                  <th>Quantité</th>
                  <th>Sous-total</th>
                </thead>
                <tbody id="detail">
                    <tr id="dfee"style="display: none">
                        <td colspan="3" style="align:right">Frais de livraison :</td>
                        <td><span id="fee"></span></td>
                    </tr>
                  <tr>
                    <td colspan="3" style="aligh:right"><b>Total</b></td>
                    <td><span id="total"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left close" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>