<?php
require_once '../db_queries/db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}


$db = new Database();
$makes = $db->read('makes');
?>



<?php
if (isset($_POST['add'])) {
    // Sanitize inputs
    $make_id = (int)$_POST['make_id'];
    $name = $db->sanitize($_POST['name']);
    $color = $db->sanitize($_POST['color']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $description = $db->sanitize($_POST['description']);

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../public/img/";
            $image_name = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES["image"]["size"] > 2000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file; // Update image path
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
            $fields = ['make_id', 'name', 'year', 'price', 'description', 'color', 'image'];
            $values = [$make_id, $name, $year, $price, $description, $color, $image_path];

            if ($db->create('models', $fields, $values)){
            echo "<p>Car added successfully!</p>";
            header("refresh:2;url=./view_models.php");
            exit;
        } else {
            echo "<p>Failed to add car.</p>";
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
    <?php include('./admin_navbar.php') ?>
    <main id="addModelMain">
        <h1>Add New Car</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            
                <label for="make">Make:</label>
                <select id="make" name="make_id" required>
                    <option value="">Select Make</option>
                <?php foreach ($makes as $make): ?>
                    <option value="<?php echo htmlspecialchars($make['id']); ?>">
                    <?php echo htmlspecialchars($make['name']); ?>
                    </option>
                <?php endforeach; ?>
                </select>
          
                <label for="name">Model Name:</label>
                <input type="text" id="name" name="name" required>
            
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" required>
           
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            
                <label for="color">Color:</label>
                <input type="text" id="color" name="color" required>
           
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
           
                <label for="image">Car Image:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            
            <button type="submit" name="add" class="btnHover">Add Car</button>
        </form>
    </main>
</body>
</html>
