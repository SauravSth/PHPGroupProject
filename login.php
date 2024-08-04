<?php
require_once './db_queries/db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $errors = [];
    
    // Validate form inputs
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    
    if (empty($errors)) {
        $db = new Database();
        
        // Fetch user from the database
        $user = $db->read('users', ['email' => $email]);
        
        if (empty($user)) {
            $errors[] = "No user found with this email.";
        } else {
            // Verify password
            if (password_verify($password, $user[0]['password'])) {
                // Secure session start
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user[0]['id'];
                $_SESSION['email'] = $user[0]['email'];
				$_SESSION['user_type'] = $user[0]['user_type'];
                
                // Redirect to a protected page or dashboard
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Invalid password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/reset.css" />
	<link rel="stylesheet" href="./public/css/styles.css" />
	<title>Login</title>
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
				<a href="./login.php">Login or Signup</a>
			</div>
		</nav>
        <main id="loginMain">
            <h2>Login</h2>
    
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    
            <form method="POST" action="login.php" id="loginForm">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"><br>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password"><br>
                
                <input type="submit" value="Login" id="loginBtn">
            </form>
    
            <a href="signup.php">Don't have an account? Sign up here</a>
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
