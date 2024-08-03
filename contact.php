<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./public/css/reset.css" />
		<link rel="stylesheet" href="./public/css/styles.css" />
		<title>Document</title>
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
		<header id="contactHeader">
			<div class="headerLeft">
				<p>CONTACT US</p>
				<h2>Got a question? We're here to help.</h2>
			</div>
			<div class="headerRight">
				<img src="./public/img/webp/honda-civic-touring.webp" alt="Car" />
			</div>
		</header>
		<main id="contactMain">
			<div class="contactWrapper">
				<div class="callOrText">
					<h2>Call or Text Us</h2>
					<p>
						Our Customer Support team is available via telephone 7
						days a week.
					</p>
					<div class="hours">
						<div>
							<strong>Monday - Friday:</strong>
							9am - 9pm EDT
						</div>
						<div>
							<strong>Saturday - Sunday:</strong>
							10am - 6pm EDT
						</div>
					</div>
					<div class="contactButton btnHoverNight">
						<h4>(123)123-1234</h4>
					</div>
				</div>
				<div class="chatWithUs">
					<h2>Chat with Us</h2>
					<p>
						We're here for you in real time. Chat with our Customer
						Support team, or send in your question overnight and
						we'll get back yo you the next day.
					</p>
					<div class="hours">
						<div>
							<strong>Monday - Friday:</strong>
							9am - 9pm EDT
						</div>
						<div>
							<strong>Saturday - Sunday:</strong>
							10am - 6pm EDT
						</div>
					</div>
					<div class="contactButton btnHoverNight">
						<h4>Start live chat</h4>
					</div>
				</div>
				<div class="contactForm">
					<h2>Submit a question</h2>
					<p>
						Submit a question though our contact form below and
						we'll get back to you as soon as possible.
					</p>
					<form action="">
						<label for="firstName">First Name</label>
						<input type="text" name="firstName" id="firstName" />
						<label for="lastName">Last Name</label>
						<input type="text" name="lastName" id="lastName" />
						<label for="email">Email</label>
						<input type="text" name="email" id="email" />
						<label for="whatCanWeHelpYouWith"
							>What can we help you with?</label
						>
						<select
							name="whatCanWeHelpYouWith"
							id="whatCanWeHelpYouWith"
						>
							<option value=""></option>
							<option value="General">General Questions</option>
							<option value="More Information">
								More Information
							</option>
							<option value="Technical Support">
								Technical Support
							</option>
							<option value="Financing">Financing</option>
						</select>
						<label for="message">Message</label>
						<textarea
							name="message"
							id="message"
							cols="40"
							rows="6"
						></textarea>
						<button type="submit" class="btnHover">Submit</button>
					</form>
				</div>
			</div>
		</main>
		<section id="ourLocations">
			<div class="locationWrapper">
				<h1>Our Locations</h1>
				<div class="officeLocation">
					<h3>Ontario</h3>
					<div class="location">
						<div class="pickup">
							<p>
								<strong>Production and Pick-Up Facility</strong>
							</p>
							<p>123 College Avenue</p>
							<p>Toronto, ON</p>
						</div>
						<div class="headquarters">
							<p>
								<strong>Corporate Headquarters</strong>
							</p>
							<p>10 College Street East</p>
							<p>Toronto, ON</p>
						</div>
					</div>
				</div>
				<div class="officeLocation">
					<h3>Nova Scotia</h3>
					<div class="location">
						<div class="pickup">
							<p>
								<strong>Vehicle Storage</strong>
							</p>
							<p>123 College Avenue</p>
							<p>Sydney, NS</p>
						</div>
						<div class="headquarters">
							<p>
								<strong>Corporate Headquarters</strong>
							</p>
							<p>10 College Street East</p>
							<p>Sydney, NS</p>
						</div>
					</div>
				</div>
			</div>
		</section>
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
