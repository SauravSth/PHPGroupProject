

<!-- Form to add a new car -->
<form method="post" action="add_car.php" enctype="multipart/form-data">
    Make ID: <input type="number" name="make_id" required><br>
    Name: <input type="text" name="name" required><br>
    Year: <input type="number" name="year" required><br>
    Price: <input type="text" name="price" required><br>
    Description: <textarea name="description" required></textarea><br>
    Color: <input type="text" name="color" required><br>
    Image: <input type="file" name="image" accept="image/*" required><br>
    <input type="submit" name="add" value="Add Car">
</form>

<!-- CREATE -->
<?php
require_once 'db_conn.php';

$db = new Database();

if (isset($_POST['add'])) {
    // Sanitize inputs
    $make_id = (int)$_POST['make_id'];
    $name = $db->sanitize($_POST['name']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $description = $db->sanitize($_POST['description']);
    $color = $db->sanitize($_POST['color']);
    
    // Handle image upload
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . $image_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["image"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // File uploaded successfully
            $image_path = $target_file;

            // Insert car details into the database
            $fields = ['make_id', 'name', 'year', 'price', 'description', 'color', 'image'];
            $values = [$make_id, $name, $year, $price, $description, $color, $image_path];

            if ($db->create('models', $fields, $values)) {
                echo "Car added successfully!";
            } else {
                echo "Error adding car.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>



<!-- READ -->


<?php
require_once 'db_conn.php';

$db = new Database();

// Example: Fetching all cars from the 'models' table
$cars = $db->read('models');

if ($cars) {
    foreach ($cars as $car) {
        echo "<div>";
        echo "<h2>" . htmlspecialchars($car['name']) . " (" . htmlspecialchars($car['year']) . ")</h2>";
        echo "<p>Price: $" . htmlspecialchars($car['price']) . "</p>";
        echo "<p>Description: " . htmlspecialchars($car['description']) . "</p>";
        echo "<p>Color: " . htmlspecialchars($car['color']) . "</p>";
        echo "<img src='" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "'>";
        echo "<a href='edit_car.php?id=" . htmlspecialchars($car['id']) . "'>Edit</a> | ";
        echo "<a href='delete_car.php?id=" . htmlspecialchars($car['id']) . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "No cars available.";
}
?>



<!-- UPDATE -->

<?php
require_once 'db_conn.php';

$db = new Database();

if (isset($_POST['update'])) {
    $id = (int)$_POST['id']; // Car ID to update
    $name = $db->sanitize($_POST['name']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $description = $db->sanitize($_POST['description']);
    $color = $db->sanitize($_POST['color']);
    $image = $db->sanitize($_POST['image']); // Assuming you have an image upload process

    $data = [
        'name' => $name,
        'year' => $year,
        'price' => $price,
        'description' => $description,
        'color' => $color,
        'image' => $image
    ];
    $conditions = ['id' => $id];

    if ($db->update('models', $data, $conditions)) {
        echo "Car updated successfully!";
    } else {
        echo "Error updating car.";
    }
}
?>

<!-- Form to edit a car -->
<form method="post" action="">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
    Name: <input type="text" name="name" required><br>
    Year: <input type="number" name="year" required><br>
    Price: <input type="text" name="price" required><br>
    Description: <textarea name="description" required></textarea><br>
    Color: <input type="text" name="color" required><br>
    Image: <input type="text" name="image"><br>
    <input type="submit" name="update" value="Update Car">
</form>


<!-- DELETE -->

<?php
require_once 'db_conn.php';

$db = new Database();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Car ID to delete

    $conditions = ['id' => $id];

    if ($db->delete('models', $conditions)) {
        echo "Car deleted successfully!";
    } else {
        echo "Error deleting car.";
    }
}
?>
