<?php
      require_once './db_queries/db.php';

      $db = new Database();

     	$models = $db->read('models', [], 3);
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./public/css/reset.css" />
		<link rel="stylesheet" href="./public/css/styles.css" />
		<title>Car Store | Home</title>
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
			<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin'): ?>
				<a href="./admin_dashboard.php">Admin Dashboard</a>
			<?php endif; ?>

			<?php if (isset($_SESSION['user_id'])): ?>
				<a href="./logout.php">Logout</a>
			<?php else: ?>
				<a href="./login.php">Login or Signup</a>
			<?php endif; ?>
			</div>
		</nav>
		<header id="indexHeader">
			<div class="bannerWrapper">
				<div class="banner">
					<img src="./public/img/webp/ford-f150xt.webp" alt="Car" />
					<div class="bannerText">
						The smart way to buy or sell a car
					</div>
				</div>
				<div class="shopCarContainer">
					<h1>Shop Cars</h1>
					<div class="containerText">
						<span>
							Hundreds of vehicles to fit all tastes and budgets
						</span>
						<span class="iconNight"
							><img
								src="./public/img/icons/arrow-sm-right-svgrepo-com.svg"
								alt=""
						/></span>
					</div>
				</div>
			</div>
		</header>
		<main id="indexMain">
			<div class="featuredCars">
				<?php
					if (!empty($models)){
						foreach ($models as $model) {
						$makeResult = $db->read('makes', ['id' => $model['make_id']],1);
						$make = $makeResult[0]['name'] ?? 'Unknown';

						echo '<div class="carCard">';
						echo '  <div class="cardHeader">';
						echo '      <img src="./public/img' . htmlspecialchars($model['image']) . '" alt="' . htmlspecialchars($model['name']) . '"/>';
						echo '  </div>';
						echo '  <div class="cardBody">';
						echo '      <p class="carTitle"><strong>' . htmlspecialchars($make) . ' ' . htmlspecialchars($model['name']) . '</strong></p>';
						echo '      <p class="carPrice">$' . number_format($model['price'], 2) . '</p>';
						echo '  </div>';
						echo '</div>';
						}
					}
				?>
				
				<a class="moreBtn btnHover" href="./shop.php">View All</a>
			</div>
			<section id="reviews">
				<div class="reviewWrapper">
					<div class="reviewCard">
						<div class="cardHeader">
							<img
								src="./public/img/webp/dodge-challenger-1.webp"
								alt="Car"
							/>
						</div>
						<div class="cardBody">
							<div class="rating">
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
							</div>
							<p class="reviewDescription">
								Culpa ullamco proident ullamco cupidatat ex aute
								ea ad ipsum amet aliqua.
							</p>
							<div class="reviewerDetails">
								<p><strong>User Name</strong></p>
							</div>
						</div>
					</div>
					<div class="reviewCard">
						<div class="cardHeader">
							<img
								src="./public/img/webp/hyundai-elantra.webp"
								alt="Car"
							/>
						</div>
						<div class="cardBody">
							<div class="rating">
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
							</div>
							<p class="reviewDescription">
								Culpa ullamco proident ullamco cupidatat ex aute
								ea ad ipsum amet aliqua.
							</p>
							<div class="reviewerDetails">
								<p><strong>User Name</strong></p>
							</div>
						</div>
					</div>
					<div class="reviewCard">
						<div class="cardHeader">
							<img
								src="./public/img/webp/tesla-model-3.webp"
								alt="Car"
							/>
						</div>
						<div class="cardBody">
							<div class="rating">
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
							</div>
							<p class="reviewDescription">
								Culpa ullamco proident ullamco cupidatat ex aute
								ea ad ipsum amet aliqua.
							</p>
							<div class="reviewerDetails">
								<p><strong>User Name</strong></p>
							</div>
						</div>
					</div>
					<div class="reviewCard">
						<div class="cardHeader">
							<img
								src="./public/img/webp/kia-rio.webp"
								alt="Car"
							/>
						</div>
						<div class="cardBody">
							<div class="rating">
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
								<img
									src="./public/img/icons/star-sharp-svgrepo-com.svg"
									alt="stars"
									class="stars"
								/>
							</div>
							<p class="reviewDescription">
								Culpa ullamco proident ullamco cupidatat ex aute
								ea ad ipsum amet aliqua.
							</p>
							<div class="reviewerDetails">
								<p><strong>User Name</strong></p>
							</div>
						</div>
					</div>
				</div>
			</section>
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
