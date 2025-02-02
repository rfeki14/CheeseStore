<?php
include 'includes/session.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $conn = $pdo->open();

    // Get transaction details
    $stmt = $conn->prepare("SELECT *, sales.id AS salesid FROM sales WHERE id=:id");
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    // Get product details for the transaction
    $stmt = $conn->prepare("SELECT * FROM details 
        LEFT JOIN products ON products.id = details.product_id 
        WHERE sales_id=:id");
    $stmt->execute(['id' => $id]);

    $list = '';
    $total = 0;

    foreach ($stmt as $details) {
        $subtotal = $details['price'] * $details['quantity'];
        $total += $subtotal;
        $list .= "
            <tr>
                <td>".$details['name']."</td>
                <td>&#36; ".number_format($details['price'], 2)."</td>
                <td>".$details['quantity']."</td>
                <td>&#36; ".number_format($subtotal, 2)."</td>
            </tr>";
    }

    $pdo->close();

    echo json_encode([
        'date' => date('M d, Y', strtotime($row['sales_date'])),
        'transaction' => $row['id'],
        'list' => $list,
        'total' => "&#36; ".number_format($total, 2)
    ]);
}
?>
