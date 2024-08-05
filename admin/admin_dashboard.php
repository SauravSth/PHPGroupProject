<?php
require_once '../db_queries/db.php';

// Start session and check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
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
    <link rel="stylesheet" href="./public/css/styles.css">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            text-align: center;
        }
        .card h3 {
            margin: 0;
            font-size: 24px;
        }
        .card p {
            font-size: 18px;
            color: #555;
        }
        .navbar {
            background: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="./admin_dashboard.php">Dashboard</a>
        <a href="./users.php">Manage Users</a>
        <a href="./car_models.php">Manage Car Models</a>
        <a href="../customer/logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <div class="card">
            <h3>Number of Makes</h3>
            <p><?php echo $makesCount; ?></p>
            <a href="./view_makes.php">View Makes</a>
        </div>
        <div class="card">
            <h3>Number of Models</h3>
            <p><?php echo $modelsCount; ?></p>
            <a href="./view_models.php">View Models</a>
        </div>
        <div class="card">
            <h3>Number of Users</h3>
            <p><?php echo $usersCount; ?></p>
            <a href="./view_users.php">View Users</a>
        </div>
        <div class="card">
            <h3>Number of Orders</h3>
            <p><?php echo $ordersCount; ?></p>
            <a href="./view_orders.php">View Orders</a>
        </div>
    </div>
</body>
</html>


