<?php
require_once '../db_queries/db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../customer/login.php");
    exit;
}


$db = new Database();
$makes = $db->read('makes');
?>



<?php
if (isset($_POST['add'])) {
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
    
    // Validate
    if (empty($firstname)) {
        $errors[] = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
        $errors[] = "First name can only contain letters, hyphens, apostrophes, and spaces.";
    }

    // Validate last name
    if (empty($lastname)) {
        $errors[] = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
        $errors[] = "Last name can only contain letters, hyphens, apostrophes, and spaces.";
    }

    // Validate address
    if (empty($address)) {
        $errors[] = "Address is required.";
    } elseif (strlen($address) < 5) {
        $errors[] = "Address should be at least 5 characters long.";
    }

    // Validate phone number
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
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
        
        $existingEmail = $db->read('users', ['email' => $email]);
        if (!empty($existingEmail)) {
            $errors[] = "Email already registered.";
        }
        
        if (empty($errors)) {
            $db->create('users', 
                ['first_name', 'last_name', 'address', 'phone_number', 'email', 'password', 'user_type'], 
                [$firstname, $lastname, $address, $phone, $email, $hashedPassword, $userType]
            );
            $successMessage = "User Added";
            header("refresh:2;url=./view_users.php");
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
      <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <?php include('./admin_navbar.php'); ?>
    <main id="addUserMain">
        <h1>Add New User</h1>
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
        
        <form method="POST" action="" id="signinForm" class="adminForm">
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

            <button type="submit" name="add" class="btnHover">Add User</button>
        </form>
    </main>
</body>
</html>
