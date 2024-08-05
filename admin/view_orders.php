<?php
require_once '../db_queries/db.php';

// Ensure user is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../customer/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <?php include('./admin_navbar.php')?>
    <main id="viewOrderMain">
        <h1>Manage Orders</h1>

        <?php
        $db = new Database();

        // Fetch all orders
        $orders = $db->read('orders');

        if ($orders) {
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Order ID</th>";
            echo "<th>User ID</th>";
            echo "<th>Total Amount</th>";
            echo "<th>Created At</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($orders as $order) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                echo "<td>" . htmlspecialchars($order['user_id']) . "</td>";
                echo "<td>$" . number_format($order['total_amount'], 2) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($order['created_at'])) . "</td>";
                echo "<td>";
                echo "<a href='../customer/invoice.php?order_id=" . htmlspecialchars($order['id']) . "' target='_blank'>Generate Invoice</a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No orders found.</p>";
        }
        ?>
    </main>
</body>
</html>
