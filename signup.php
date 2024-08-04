<?php
require_once ("./db_queries/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    
    $errors = [];
    
    // Validate form inputs
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
        $db = new Database();
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if the email already exists
        $existingEmail = $db->read('users', ['email' => $email]);
        if (!empty($existingEmail)) {
            $errors[] = "Email already registered.";
        }
        
        if (empty($errors)) {
            // Insert new user into the database
            $db->create('users', ['email', 'password'], [$email, $hashedPassword]);
            $successMessage = "Registration successful. You can now log in.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/reset.css">

    <link href="./public/css/styles.css" rel="stylesheet" />
    <title>Sign Up</title>
</head>
<body>
    		<?php include './nav.php' ?>

    <h1>Sign Up</h1>
    
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
    
    <form method="POST" action="signup.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br>
        
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password"><br>
        
        <input type="submit" value="Sign Up">
    </form>
    
    <a href="login.php">Already have an account? Log in here</a>
    		<?php include './footer.php' ?>

</body>
</html>
