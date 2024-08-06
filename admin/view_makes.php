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
        <h1>View Models</h1>
        <?php
        $db = new Database();

$makes = $db->read('makes');

if ($makes) {
    foreach ($makes as $make) {
        echo "<div>";
        echo "<ul>";
        echo "<li>" . htmlspecialchars($make['name']) . "</li>";
        echo "<hr>";
        echo "</ul>";
    }
} else {
    echo "No makes available.";
}
?>
    </main>
</body>
</html>
