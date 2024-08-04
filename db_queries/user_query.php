<?php


// CREATE
require_once 'db.php';

$db = new Database();

// Example: Inserting a new user into the 'users' table
$username = $db->sanitize('johndoe');
$password = password_hash('password123', PASSWORD_BCRYPT);
$email = $db->sanitize('johndoe@example.com');
$first_name = $db->sanitize('John');
$last_name = $db->sanitize('Doe');
$phone_number = $db->sanitize('1234567890');
$address = $db->sanitize('123 Main St');
$user_type = 'customer';

$fields = ['username', 'password', 'email', 'first_name', 'last_name', 'phone_number', 'address', 'user_type'];
$values = [$username, $password, $email, $first_name, $last_name, $phone_number, $address, $user_type];

if ($db->create('users', $fields, $values)) {
    echo "User created successfully!";
} else {
    echo "Error creating user.";
}



// READ

require_once 'db.php';

$db = new Database();

// Example: Fetching a user by username from the 'users' table
$conditions = ['username' => 'johndoe'];
$user = $db->read('users', $conditions);

if ($user) {
    print_r($user);
} else {
    echo "User not found.";
}



// UPDATE
require_once 'db.php';

$db = new Database();

// Example: Updating a user's email and address in the 'users' table
$data = [
    'email' => $db->sanitize('newemail@example.com'),
    'address' => $db->sanitize('456 New St')
];
$conditions = ['username' => 'johndoe'];

if ($db->update('users', $data, $conditions)) {
    echo "User updated successfully!";
} else {
    echo "Error updating user.";
}




// DELETE 
require_once 'db.php';

$db = new Database();

// Example: Deleting a user from the 'users' table
$conditions = ['username' => 'johndoe'];

if ($db->delete('users', $conditions)) {
    echo "User deleted successfully!";
} else {
    echo "Error deleting user.";
}


?>
