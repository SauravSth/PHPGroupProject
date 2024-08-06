<?php
require_once './db.php';

session_start();  

if (!isset($_SESSION['user_id'])) {
    // Redirect to login 
    header("Location: ../customer/login.php");
    exit();
}

if (isset($_GET['model_id'])) {
    $modelId = intval($_GET['model_id']);
    $userId = $_SESSION['user_id']; 

    $cart = new Cart();

    
    if ($cart->addItem($userId, $modelId, 1)) {
        header("Location: ../customer/shop.php?success=Item added to cart");
    } else {
        header("Location: ../customer/shop.php");
    }
} else {
    header("Location: ../customer/shop.php?error=Invalid request");
}
?>
