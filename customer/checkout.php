<?php
require_once '../db_queries/db.php';



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
    <link href="../public/css/styles.css" rel="stylesheet" />
    <title>Checkout</title>
</head>
<body>
<?php include './nav.php' ?>
    
    <main id="checkoutMain">
        <h2>Checkout</h2>

        <?php if (isset($successMessage)): ?>
            <div class="successMessage">
                <p><?php echo htmlspecialchars($successMessage); ?></p>
                <a href="invoice.php?order_id=<?php echo $orderId; ?>" class="btnHover border">Download Invoice</a>
            </div>
            <a href="shop.php" class="btnHover border">Continue Shopping</a>
        <?php elseif (isset($errorMessage)): ?>
            <div class="errorMessage">
                <p><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php endif; ?>
    </main>
    	<?php include './footer.php' ?>
</body>
</html>
