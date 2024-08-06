<?php
require_once '../db_queries/db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../customer/login.php");
    exit;
}

$db = new Database();

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$user_id) {
    die('Invalid User ID');
}

$user = $db->read('users', ['id' => $user_id]);
if (empty($user)) {
    die('User not found');
}
$user = $user[0];

if (isset($_POST['update'])) {
    // Sanitize inputs
    $firstname = $db->sanitize(trim($_POST['firstname']));
    $lastname = $db->sanitize(trim($_POST['lastname']));
    $address = $db->sanitize(trim($_POST['address']));
    $phone = $db->sanitize(trim($_POST['phone']));
    
    $errors = [];
    
    if (empty($firstname)) {
        $errors[] = "First name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $firstname)) {
        $errors[] = "First name can only contain letters, hyphens, apostrophes, and spaces.";
    }

    if (empty($lastname)) {
        $errors[] = "Last name is required.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $lastname)) {
        $errors[] = "Last name can only contain letters, hyphens, apostrophes, and spaces.";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    } elseif (strlen($address) < 5) {
        $errors[] = "Address should be at least 5 characters long.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Phone number must be exactly 10 digits.";
    }

    if (empty($errors)) {
        $db->update('users', 
            ['first_name' => $firstname, 'last_name' => $lastname, 'address' => $address, 'phone_number' => $phone], 
            ['id' => $user_id]
        );
        $successMessage = "User Updated";
        header("refresh:2;url=./view_users.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <?php 
        include("./admin_navbar.php");
    ?>
<main id="editUserMain">
        <h1>Edit User</h1>
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
        
        <form method="POST" action="" id="editUserForm">
            <label for="firstname">First Name:</label>
            <input type="text" name="firstname" id="firstname" value="<?php echo htmlspecialchars($user['first_name']); ?>" autocomplete="off"><br>

            <label for="lastname">Last Name:</label>
            <input type="text" name="lastname" id="lastname" value="<?php echo htmlspecialchars($user['last_name']); ?>" autocomplete="off"><br>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user['address']); ?>" autocomplete="off"><br>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($user['phone_number']); ?>" autocomplete="off"><br>

            <input type="submit" value="Update User" name="update" class="btnHover">
        </form>
    </main>
</body>
</html>
