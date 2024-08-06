<?php
require_once '../db_queries/db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$db = new Database();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; 

    $conditions = ['id' => $id];

    if ($db->delete('models', $conditions)) {
        echo "Car deleted successfully!";
         header("refresh:2;url=./view_models.php");
    } else {
        echo "Error deleting car.";
    }
}
?>