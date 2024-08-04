<?php
	require_once './db_queries/db.php';

	$db = new Database();
	$car_id = $_GET['id'];

	$car = $db->read('models', ['id' => $car_id])[0]; 

	$make = $db->read('makes', ['id' => $car['make_id']])[0]['name'];
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
		<nav>
			<div class="navLeft">
				<ul>
					<li class="logo">
						<a href="./home.php">Store Name</a>
					</li>
					<li><a href="./shop.php">Shop Cars</a></li>
					<li><a href="./contact.php">Contact Us</a></li>
				</ul>
			</div>
			<div class="navRight">
				<a href="login">Login or Signup</a>
			</div>
		</nav>
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
				<p>$50 Shipping</p>
				<a href="" class="btnHover">Start Purchase</a>
				</div>
			</div>
		</main>
		<footer>
			<div id="footerWrapper">
				<div class="browseByMake">
					<p><strong>Browse By Make and Model</strong></p>
					<ul>
						<li>Dodge Challenger</li>
						<li>Ford F-150</li>
						<li>Ford Mustang</li>
						<li>Honda Civic</li>
						<li>Hyundai Elantra</li>
					</ul>
				</div>
				<div class="browseByStyle">
					<p><strong>Browse By Style</strong></p>
					<ul>
						<li>SUV</li>
						<li>Sedan</li>
						<li>Hatchback</li>
						<li>Truck</li>
						<li>Van</li>
					</ul>
				</div>
				<div class="browseByLocation">
					<p><strong>Browse By Location</strong></p>
					<ul>
						<li>Toronto</li>
						<li>Kitchener</li>
						<li>Waterloo</li>
						<li>Cambridge</li>
						<li>Barrie</li>
					</ul>
				</div>
				<div class="explore">
					<p><strong>Explore</strong></p>
					<ul>
						<li>Home</li>
						<li>Shop Cars</li>
						<li>Sell or Trade</li>
						<li>Finance</li>
						<li>Vehicle Protection</li>
					</ul>
				</div>
				<div class="company">
					<p><strong>Company</strong></p>
					<ul>
						<li>About Us</li>
						<li>Careers</li>
						<li>Blog</li>
						<li>FAQ</li>
					</ul>
				</div>
				<div class="contactUs">
					<p><strong>Contact Us</strong></p>
					<ul>
						<li>Chat with us</li>
						<li>Call us at (123)123-1234</li>
						<li>Email us at: test@demo.com</li>
					</ul>
				</div>
			</div>
		</footer>
	</body>
</html>
