<?php
require_once ("../fpdf186/fpdf.php");
require_once ("../db_queries/db.php");


// Fetch the order_id from query parameter
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

if (!$order_id) {
    die('Invalid Order ID');
}

// Create a new instance of the Database class
$db = new Database();

// Fetch order details
$order = $db->read('orders', ['id' => $order_id]);
if (empty($order)) {
    die('Order not found');
}
$order = $order[0]; // Get the first result

// Fetch order items
$order_items = $db->read('order_items', ['order_id' => $order_id]);

if (empty($order_items)) {
    die('No items found for this order');
}

// Create instance of FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add company logo
$pdf->Image('../public/img/logo/logo.png', 10, 10, 30); // Adjust path and size as needed

$pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
$pdf->Ln(10);

// Add order details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Order ID: ' . $order['id'], 0, 1);
$pdf->Cell(0, 10, 'Date: ' . date('Y-m-d', strtotime($order['created_at'])), 0, 1);
$pdf->Cell(0, 10, 'Total Amount: $' . number_format($order['total_amount'], 2), 0, 1);
$pdf->Ln(10);

// Add table header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(90, 10, 'Product', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(30, 10, 'Price', 1);
$pdf->Cell(30, 10, 'Total', 1);
$pdf->Ln();

// Add order items
$pdf->SetFont('Arial', '', 12);
foreach ($order_items as $item) {
    $model = $db->read('models', ['id' => $item['model_id']]);
    $model = $model[0]; // Get the first result

    $pdf->Cell(90, 10, $model['name'], 1);
    $pdf->Cell(30, 10, $item['quantity'], 1);
    $pdf->Cell(30, 10, '$' . number_format($item['price'], 2), 1);
    $pdf->Cell(30, 10, '$' . number_format($item['quantity'] * $item['price'], 2), 1);
    $pdf->Ln();
}

// Add footer
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 0, 'C');

// Output PDF
$pdf->Output('I', 'Invoice_' . $order_id . '.pdf'); // Output inline (browser view)
?>