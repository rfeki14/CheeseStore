<?php 
  include 'includes/session.php';
  include 'includes/format.php'; 
?>
<?php 
  $today = date('Y-m-d');
  $year = date('Y');
  $month = date('m');
  if(isset($_GET['year'])){
    $year = $_GET['year'];
  }

  $conn = $pdo->open();

  // Récupérer le nombre total de produits
  $stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
  $stmt->execute();
  $total_products = $stmt->fetch()['total_products'];

  // Récupérer le nombre total de ventes
  $stmt = $conn->prepare("SELECT COUNT(*) AS total_sales FROM sales");
  $stmt->execute();
  $total_sales = $stmt->fetch()['total_sales'];

  // Récupérer le nombre de nouveaux utilisateurs ce mois-ci
  $stmt = $conn->prepare("SELECT COUNT(*) AS new_users FROM users WHERE MONTH(created_on) = :month AND YEAR(created_on) = :year");
  $stmt->execute(['month' => $month, 'year' => $year]);
  $new_users = $stmt->fetch()['new_users'];

  // Récupérer les produits les plus vendus
  $stmt = $conn->prepare("SELECT products.name, SUM(details.quantity) AS total_quantity 
                          FROM details 
                          LEFT JOIN products ON products.id = details.product_id 
                          GROUP BY products.id 
                          ORDER BY total_quantity DESC 
                          LIMIT 5");
  $stmt->execute();
  $top_products = $stmt->fetchAll();

  // Récupérer les meilleurs clients
  $stmt = $conn->prepare("SELECT users.firstname, users.lastname, SUM(e.price * details.quantity) AS total_spent 
                          FROM sales 
                          LEFT JOIN details ON details.sales_id = sales.id 
                          LEFT JOIN edition e ON e.id = details.product_id
                          LEFT JOIN users ON users.id = sales.user_id 
                          GROUP BY users.id 
                          ORDER BY total_spent DESC 
                          LIMIT 5");
  $stmt->execute();
  $best_clients = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="dist/css/dashboard.css">

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Conteneur de contenu. Contient le contenu de la page -->
  <div class="content-wrapper">
    <!-- En-tête de contenu (en-tête de page) -->
    <section class="content-header">
      <h1>
        Tableau de Bord
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Accueil</a></li>
        <li class="active">Tableau de Bord</li>
      </ol>
    </section>

    <!-- Contenu principal -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Erreur!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Succès!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <!-- Petites boîtes (Statistiques) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- petite boîte -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <?php
                $stmt = $conn->prepare("SELECT details.*, e.price FROM details 
                         LEFT JOIN edition e ON e.id=details.product_id");
                $stmt->execute();

                $total = 0;
                foreach($stmt as $srow){
                  $subtotal = $srow['price']*$srow['quantity'];
                  $total += $subtotal;
                }

                echo "<h3>&#36; ".number_format_short($total, 2)."</h3>";
              ?>
              <p>Ventes Totales</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="book.php" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- petite boîte -->
          <div class="small-box bg-green">
            <div class="inner">
              <?php
                $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM products");
                $stmt->execute();
                $prow =  $stmt->fetch();

                echo "<h3>".$prow['numrows']."</h3>";
              ?>
              <p>Nombre de Produits</p>
            </div>
            <div class="icon">
              <i class="fa fa-barcode"></i>
            </div>
            <a href="student.php" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- petite boîte -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <?php
                $stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM users");
                $stmt->execute();
                $urow =  $stmt->fetch();

                echo "<h3>".$urow['numrows']."</h3>";
              ?>
              <p>Nombre d'Utilisateurs</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="return.php" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- petite boîte -->
          <div class="small-box bg-red">
            <div class="inner">
              <?php
                $stmt = $conn->prepare("SELECT details.*, e.price 
                         FROM details 
                         LEFT JOIN sales ON sales.id=details.sales_id 
                         LEFT JOIN edition e ON e.id=details.product_id 
                         WHERE sales_date=:sales_date");
                $stmt->execute(['sales_date'=>$today]);

                $total = 0;
                foreach($stmt as $trow){
                  $subtotal = $trow['price']*$trow['quantity'];
                  $total += $subtotal;
                }

                echo "<h3>&#36; ".number_format_short($total, 2)."</h3>";
              ?>
              <p>Ventes Aujourd'hui</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="borrow.php" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- Statistiques Supplémentaires -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- petite boîte -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3><?php echo $new_users; ?></h3>
              <p>Nouveaux Utilisateurs Ce Mois</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-plus"></i>
            </div>
            <a href="new_users.php" class="small-box-footer">Plus d'infos <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->

      <!-- Produits Top -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Top 5 Produits</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Nom du Produit</th>
                    <th>Quantité Totale Vendue</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($top_products as $product){
                      echo "
                        <tr>
                          <td>".$product['name']."</td>
                          <td>".$product['total_quantity']."</td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </ div>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <!-- Meilleurs Clients -->
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Top 5 Clients</h3>
            </div>
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Total Dépensé</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($best_clients as $client){
                      echo "
                        <tr>
                          <td>".$client['firstname']." ".$client['lastname']."</td>
                          <td>&#36; ".number_format($client['total_spent'], 2)."</td>
                        </tr>
                      ";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Rapport de Ventes Mensuel</h3>
              <div class="box-tools pull-right">
                <form class="form-inline">
                  <div class="form-group">
                    <label>Sélectionner l'Année: </label>
                    <select class="form-control input-sm" id="select_year">
                      <?php
                        for($i=2015; $i<=2065; $i++){
                          $selected = ($i==$year)?'selected':'';
                          echo "
                            <option value='".$i."' ".$selected.">".$i."</option>
                          ";
                        }
                      ?>
                    </select>
                  </div>
                </form>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <br>
                <div id="legend" class="text-center"></div>
                <canvas id="barChart" style="height:350px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      </section>
      <!-- colonne droite -->
    </div>
      <?php include 'includes/footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- Données du Graphique -->
<?php
  $months = array();
  $sales = array();
  for( $m = 1; $m <= 12; $m++ ) {
    try{
      $stmt = $conn->prepare("SELECT details.*, e.price 
                         FROM details 
                         LEFT JOIN sales ON sales.id=details.sales_id 
                         LEFT JOIN edition e ON e.id=details.product_id 
                         WHERE MONTH(sales_date)=:month AND YEAR(sales_date)=:year");
      $stmt->execute(['month'=>$m, 'year'=>$year]);
      $total = 0;
      foreach($stmt as $srow){
        $subtotal = $srow['price']*$srow['quantity'];
        $total += $subtotal;    
      }
      array_push($sales, round($total, 2));
    }
    catch(PDOException $e){
      echo $e->getMessage();
    }

    $num = str_pad( $m, 2, 0, STR_PAD_LEFT );
    $month =  date('M', mktime(0, 0, 0, $m, 1));
    array_push($months, $month);
  }

  $months = json_encode($months);
  $sales = json_encode($sales);

?>
<!-- Fin des Données du Graphique -->

<?php $pdo->close(); ?>
<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
  var barChartCanvas = $('#barChart').get(0).getContext('2d')
  var barChart = new Chart(barChartCanvas)
  var barChartData = {
    labels  : <?php echo $months; ?>,
    datasets: [
      {
        label               : 'VENTES',
        fillColor           : 'rgba(60,141,188,0.9)',
        strokeColor         : 'rgba(60,141,188,0.8)',
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : <?php echo $sales; ?>
      }
    ]
  }
  var barChartOptions                  = {
    scaleBeginAtZero        : true,
    scaleShowGridLines      : true,
    scaleGrid LineColor      : 'rgba(0,0,0,.05)',
    scaleGridLineWidth      : 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines  : true,
    barShowStroke           : true,
    barStrokeWidth          : 2,
    barValueSpacing         : 5,
    barDatasetSpacing       : 1,
    legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
    responsive              : true,
    maintainAspectRatio     : true
  }

  barChartOptions.datasetFill = false
  var myChart = barChart.Bar(barChartData, barChartOptions)
  document.getElementById('legend').innerHTML = myChart.generateLegend();
});
</script>
<script>
$(function(){
  $('#select_year').change(function(){
    window.location.href = 'home.php?year='+$(this).val();
  });
});
</script>
</body>
</html>