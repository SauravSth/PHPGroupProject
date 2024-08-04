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
    <link href="./public/css/styles.css" rel="stylesheet" />
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"><br>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password"><br>
        
        <input type="submit" value="Login">
    </form>
    
    <a href="signup.php">Don't have an account? Sign up here</a>
</body>
</html>
