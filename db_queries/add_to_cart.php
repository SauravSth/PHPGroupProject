<?php
require_once './db.php';

session_start();  // Start session

if (!isset($_SESSION['user_id'])) {
    // Redirect to login or show error if user is not logged in

    // echo("Please Login");
    header("Location: ../customer/login.php");
    exit();
}

if (isset($_GET['model_id'])) {
    $modelId = intval($_GET['model_id']);
    $userId = $_SESSION['user_id'];  // Assuming user_id is stored in session

    // Instantiate Cart class
    $cart = new Cart();

    // Add item to cart with quantity 1
    if ($cart->addItem($userId, $modelId, 1)) {
        // Redirect or show success message
        header("Location: ../customer/shop.php?success=Item added to cart");
    } else {
        // Redirect or show error message
        header("Location: ../customer/shop.php");
    }
} else {
    // Redirect or show error if model_id is not provided
    header("Location: ../index.php?error=Invalid request");
}
?>
