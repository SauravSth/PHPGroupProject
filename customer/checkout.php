<?php
require_once '../db_queries/db.php';
require_once './Cart.php';

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = new Cart();
$userId = $_SESSION['user_id'];

if ($cart->checkout($userId)) {
    $orderId = $cart->getLastOrderId(); // Assuming you have a method to retrieve the last order ID
    $successMessage = "Checkout successful! Your order has been placed.";
} else {
    $errorMessage = "Checkout failed. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="./public/css/styles.css">
    <title>Checkout</title>
</head>
<body>
    <?php include('./nav.php') ?>
    
    <main id="checkoutMain">
        <h2>Checkout</h2>

        <?php if (isset($successMessage)): ?>
            <div class="successMessage">
                <p><?php echo htmlspecialchars($successMessage); ?></p>
                <p><a href="invoice.php?order_id=<?php echo $orderId; ?>" target="_blank">Download Invoice</a></p>
            </div>
            <a href="shop.php">Continue Shopping</a>
        <?php elseif (isset($errorMessage)): ?>
            <div class="errorMessage">
                <p><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php endif; ?>
    </main>
    <?php include('./footer.php') ?>

</body>
</html>
