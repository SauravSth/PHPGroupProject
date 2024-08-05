<?php
require_once ("../db_queries/db.php");

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new Database();

    // Sanitize inputs
    $firstname = $db->sanitize(trim($_POST['firstname']));
    $lastname = $db->sanitize(trim($_POST['lastname']));
    $address = $db->sanitize(trim($_POST['address']));
    $phone = $db->sanitize(trim($_POST['phone']));
    $email = $db->sanitize(trim($_POST['email']));
    $password = $db->sanitize(trim($_POST['password']));
    $confirmPassword = $db->sanitize(trim($_POST['confirm_password']));
    
    $errors = [];
    
    // Validate form inputs
    if (empty($firstname)) {
        $errors[] = "First name is required.";
    }

    if (empty($lastname)) {
        $errors[] = "Last name is required.";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }
    
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userType = 'customer';
        
        // Check if the email already exists
        $existingEmail = $db->read('users', ['email' => $email]);
        if (!empty($existingEmail)) {
            $errors[] = "Email already registered.";
        }
        
        if (empty($errors)) {
            // Insert new user into the database
            $db->create('users', 
                ['first_name', 'last_name', 'address', 'phone_number', 'email', 'password', 'user_type'], 
                [$firstname, $lastname, $address, $phone, $email, $hashedPassword, $userType]
            );
            $successMessage = "Registration successful. Redirecting to login page...";
            header("refresh:2;url=login.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/reset.css" />
    <link rel="stylesheet" href="../public/css/styles.css" />
    <title>Register</title>
</head>
<body>

<?php include './nav.php' ?>

    <main id="signinMain">
        <h2>Sign Up</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
                
        <?php if (isset($successMessage)): ?>
            <p><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        
        <form method="POST" action="signup.php" id="signinForm">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : ''; ?>" autocomplete="off"><br>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : ''; ?>" autocomplete="off"><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>" autocomplete="off"><br>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" autocomplete="off"><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" autocomplete="off"><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" autocomplete="off"><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" autocomplete="off"><br>

            <input type="submit" value="Sign Up" id="signinBtn">
        </form>

        <a href="login.php">Already have an account? Log in here</a>
    </main>

    <?php include './footer.php' ?>

</body>
</html>