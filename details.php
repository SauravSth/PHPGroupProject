<?php
	require_once './db_queries/db.php';

	$db = new Database();
	$car_id = $_GET['id'];

	$car = $db->read('models', ['id' => $car_id])[0]; 

	$make = $db->read('makes', ['id' => $car['make_id']])[0]['name'];

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    		if (isset($_SESSION['user_id'])) {
			header("Location: cart.php");
		} else {
			// User is not logged in, redirect to login page
			header("Location: login.php");
			exit();
   		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./public/css/reset.css" />
		<link rel="stylesheet" href="./public/css/styles.css" />
		<title>Car Detail</title>
	</head>
	<body>
		<?php include './nav.php' ?>

		<main id="detailMain">
			<div id="detailWrapper">
				<div class="carImage">
				<img src="./public/img<?php echo htmlspecialchars($car['image']); ?>" alt="car" />
				<div class="subImage">
					<img src="./public/img<?php echo htmlspecialchars($car['image']); ?>" alt="car" />
				</div>
				</div>
				<div class="carDetail">
				<h2><?php echo htmlspecialchars($make) . ' ' . htmlspecialchars($car['name']); ?></h2>
				<h2>$<?php echo number_format($car['price'], 2); ?></h2>
				<p><?php echo htmlspecialchars($car['description']); ?></p>
				<p><?php echo htmlspecialchars($car['color']); ?></p>
				<p>$50 Shipping</p>
				<form action="" method="post">
					<button type="submit" class="btnHover">Add to Cart</a>
				</form>
				</div>
			</div>
		</main>
		<?php include './footer.php' ?>

	</body>
</html>
