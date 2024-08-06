<?php
require_once '../db_queries/db.php';

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
    <?php include('./admin_navbar.php') ?>
    
    <main id="viewModelMain">
        <header id="viewModelHeader">
            <h1>Manage Models</h1>
            <a href="./add_models.php" class="btnHover border">Add Car </a>
        </header>
        <?php
        $db = new Database();

$cars = $db->read('models');

if ($cars) {
    foreach ($cars as $car) {
        echo "<div class='viewModelWrapper'>";
        echo "<div>";
        echo "<h2>" . htmlspecialchars($car['name']) . " (" . htmlspecialchars($car['year']) . ")</h2>";
        echo "<p>Price: $" . htmlspecialchars($car['price']) . "</p>";
        echo "<p>Description: " . htmlspecialchars($car['description']) . "</p>";
        echo "<p>Color: " . htmlspecialchars($car['color']) . "</p>";
        echo "<div class='iconWrapper'>";
        echo "<a href='edit_models.php?id=" . htmlspecialchars($car['id']) . "'><img src='../public/img/icons/edit-3-svgrepo-com.svg' class='iconAdmin'></a> ";
        echo "<a href='delete_models.php?id=" . htmlspecialchars($car['id']) . "' onclick='return confirm(\"Are you sure?\")'><img src='../public/img/icons/delete-recycle-bin-trash-can-svgrepo-com.svg' class='iconAdmin'></a>";
        echo "</div>";
        echo "<hr>";
        echo "</div>";
        echo "<img src='" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "'>";
        echo "</div>";
    }
} else {
    echo "No cars available.";
}
?>
    </main>
</body>
</html>
