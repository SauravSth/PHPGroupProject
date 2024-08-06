<?php
require_once ("../fpdf186/fpdf.php");
require_once ("../db_queries/db.php");

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    die('Invalid Order ID');
}

$db = new Database();

$order = $db->read('orders', ['id' => $order_id]);
if (empty($order)) {
    die('Order not found');
}
$order = $order[0]; 

$user = $db->read('users', ['id' => $order['user_id']]);
if (empty($user)) {
    die('User not found');
}
$user = $user[0]; 

$order_items = $db->read('order_items', ['order_id' => $order_id]);
if (empty($order_items)) {
    die('No items found for this order');
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);

$pdf->Image('../public/img/logo/logo-invoice.png', 10, 10, 50); 

$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Customer Details:', 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Name: ' . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']), 0, 1);
$pdf->Cell(0, 10, 'Email: ' . htmlspecialchars($user['email']), 0, 1);
$pdf->Cell(0, 10, 'Phone: ' . htmlspecialchars($user['phone_number']), 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Order ID: ' . $order['id'], 0, 1);
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d', strtotime($order['created_at'])), 0, 1);
$pdf->Cell(0, 10, 'Total Amount: $' . number_format($order['total_amount'], 2), 0, 1);
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Product', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
foreach ($order_items as $item) {
    $model = $db->read('models', ['id' => $item['model_id']]);
    $model = $model[0]; 

    $pdf->Cell(90, 10, htmlspecialchars($model['name']), 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
    $pdf->Cell(30, 10, '$' . number_format($item['quantity'] * $item['price'], 2), 1);
    $pdf->Ln();
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 0, 'C');

$pdf->Output('I', 'Invoice_' . $order_id . '.pdf'); 
?>
