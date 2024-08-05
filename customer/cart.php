<?php
session_start();
require_once '../db_queries/db.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header("Location: ./login.php");
    exit();
}

$db = new Database();
$userId = $_SESSION['user_id'];
$cart = new Cart();

// Handle update quantity request
if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $modelId => $quantity) {
        if (is_numeric($quantity) && $quantity > 0) {
            $cart->updateItem($userId, $modelId, intval($quantity));
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle remove item request
if (isset($_GET['remove'])) {
    $modelId = intval($_GET['remove']);
    $cart->removeItem($userId, $modelId);
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$cartItems = $cart->getCartItems($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/reset.css">
    <link href="./public/css/styles.css" rel="stylesheet" />
    <title>Cart</title>
</head>
<body>
	<?php include './nav.php' ?>

    <h1>Your Cart</h1>

    <?php if (!empty($cartItems)): ?>
        <form method="POST" action="cart.php">
            <table border="1">
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <?php
                $totalAmount = 0;
                foreach ($cartItems as $item):
                    $model = $db->read('models', ['id' => $item['model_id']])[0];
                    $make = $db->read('makes', ['id' => $model['make_id']])[0]['name'];
                    $itemTotal = $model['price'] * $item['quantity'];
                    $totalAmount += $itemTotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($make); ?></td>
                        <td><?php echo htmlspecialchars($model['name']); ?></td>
                        <td><?php echo htmlspecialchars($model['year']); ?></td>
                        <td><?php echo htmlspecialchars($model['price']); ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo htmlspecialchars($model['id']); ?>]" value="<?php echo htmlspecialchars($item['quantity']); ?>" min="1">
                        </td>
                        <td><?php echo htmlspecialchars($itemTotal); ?></td>
                        <td>
                            <a href="cart.php?remove=<?php echo htmlspecialchars($model['id']); ?>">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <p>Total Amount: <?php echo htmlspecialchars($totalAmount); ?></p>
            <input type="submit" name="update" value="Update Cart">
        </form>
        <a href="./checkout.php">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="./index.php">Continue Shopping</a>
    	<?php include './footer.php' ?>

</body>
</html>
