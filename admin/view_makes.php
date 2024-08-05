<?php
require_once '../db_queries/db.php';

// Ensure user is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Models</title>
      <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <nav>
        <div class="navLeft">
            <ul>
                <li class="logo"><a href="./admin_dashboard.php">Dashboard</a></li>
                <li><a href="./manage_users.php">Manage Users</a></li>
                <li><a href="./manage_orders.php">Manage Orders</a></li>
            </ul>
        </div>
        <div class="navRight">
            <a href="../customer/logout.php">Logout</a>
        </div>
    </nav>
    <main id="viewModelMain">
        <h1>View Models</h1>
        <?php
        $db = new Database();

// Fetch all the models data

$makes = $db->read('makes');

if ($makes) {
    foreach ($makes as $make) {
        echo "<div>";
        echo "<p>" . htmlspecialchars($make['name']) . "</p>";
        echo "<a href='edit_models.php?id=" . htmlspecialchars($make['id']) . "'>Edit</a> | ";
        echo "<a href='delete_models.php?id=" . htmlspecialchars($make['id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "No makes available.";
}
?>
    </main>
</body>
</html>
