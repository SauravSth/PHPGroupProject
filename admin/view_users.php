<?php
require_once '../db_queries/db.php';

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
    <title>Manage Users</title>
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <?php include('./admin_navbar.php') ?>
    <main id="viewUserMain">
        <h1>Manage Users</h1>
        <a href="./add_users.php" class="btnHover border">Add User</a>
        <?php
        $db = new Database();

        // Fetch all users data
        $users = $db->read('users');

        if ($users) {
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Address</th>";
            echo "<th>Email</th>";
            echo "<th>Phone Number</th>";
            echo "<th>Actions</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['address']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['phone_number']) . "</td>";
                echo "<td>";
                echo "<a href='edit_users.php?id=" . htmlspecialchars($user['id']) . "'><img src='../public/img/icons/edit-3-svgrepo-com.svg' class='iconAdmin'></a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No users available.</p>";
        }
        ?>
    </main>
</body>
</html>
