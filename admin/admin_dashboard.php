<?php
require_once '../db_queries/db.php';

// Start session and check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../customer/login.php");
    exit;
}

// Create instance of Database class
$db = new Database();

// Fetch counts
$makesCount = $db->read('makes', [], null); // Adjust table name if needed
$modelsCount = $db->read('models', [], null);
$usersCount = $db->read('users', [], null); // Adjust table name if needed
$ordersCount = $db->read('orders', [], null);

$makesCount = count($makesCount);
$modelsCount = count($modelsCount);
$usersCount = count($usersCount);
$ordersCount = count($ordersCount);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php include('./admin_navbar.php') ?>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="card">
            <h3>Number of Makes</h3>
            <p><?php echo $makesCount; ?></p><br>
            <a href="./view_makes.php" class="btnHover border">View Makes</a>
        </div>
        <div class="card">
            <h3>Number of Models</h3>
            <p><?php echo $modelsCount; ?></p><br>
            <a href="./view_models.php" class="btnHover border">View Models</a>
        </div>
        <div class="card">
            <h3>Number of Users</h3>
            <p><?php echo $usersCount; ?></p><br>
            <a href="./view_users.php" class="btnHover border">View Users</a>
        </div>
        <div class="card">
            <h3>Number of Orders</h3>
            <p><?php echo $ordersCount; ?></p><br>
            <a href="./view_orders.php" class="btnHover border">View Orders</a>
        </div>
    </div>
</body>
</html>


