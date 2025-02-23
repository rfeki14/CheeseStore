<?php
include 'includes/session.php';

$id = $_POST['id'];

$conn = $pdo->open();

$output = array('list'=>'');

$stmt = $conn->prepare("SELECT id, sales_date, delivery_method, dp_address, total , status FROM sales WHERE id=:id");
$stmt->execute(['id'=>$id]);
$row = $stmt->fetch();

if (!$row) {
    $output['error'] = 'Transaction not found';
    echo json_encode($output);
    $pdo->close();
    exit();
}

$output['fee']=0;
$output['total'] = '<b>&#36; '.number_format($row['total'], 2).'<b>';
$output['transaction'] = $row['id'];
$output['delivery_method'] = $row['delivery_method'];
$output['date'] = date('M d, Y', strtotime($row['sales_date']));
$output['status'] = $row['status']?'Completed':'Pending';

if ($row['delivery_method'] == 'pickup') {
    $stmt = $conn->prepare("SELECT s.name, a.street, a.city, a.state, a.zip_code FROM stores s LEFT JOIN address a ON s.address = a.id WHERE s.id = :store_id");
    $stmt->execute(['store_id' => $row['dp_address']]);
    $store = $stmt->fetch();

    if (!$store) {
        $output['error'] = 'Store not found';
		
    }

    $output['store'] = $store['name'];
    $output['address'] = $store['street'].' '.$store['city'].' '.$store['state'].' '.$store['zip_code'];
} else {
    $stmt = $conn->prepare("SELECT street, city, state, zip_code FROM address WHERE id = :address_id");
    $stmt->execute(['address_id' => $row['dp_address']]);
    $output['fee']=7;
    $address = $stmt->fetch();

    if (!$address) {
        $output['error'] = 'Address not found';

    }

    $output['address'] = $address['street'].' '.$address['city'].' '.$address['state'].' '.$address['zip_code'];
}

$stmt = $conn->prepare("SELECT p.name, e.price, e.weight, d.quantity FROM details d LEFT JOIN edition e on d.product_id=e.id LEFT JOIN products p ON p.id = e.product_id WHERE d.sales_id = :id");
$stmt->execute(['id'=>$id]);

$total = 0;
foreach ($stmt as $row) {
    $subtotal = $row['price'] * $row['quantity'];
    $total += $subtotal;
    $output['list'] .= "
        <tr class='prepend_items'>
            <td>".$row['name']."-".$row['weight']."g</td>
            <td>&#36; ".number_format($row['price'], 2)."</td>
            <td>".$row['quantity']."</td>
            <td>&#36; ".number_format($subtotal, 2)."</td>
        </tr>
    ";
}
$pdo->close();
echo json_encode($output);
?>