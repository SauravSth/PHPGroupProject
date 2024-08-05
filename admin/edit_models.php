<?php
require_once '../db_queries/db.php';

// Ensure user is an admin
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$db = new Database();

// Fetch model details
if (isset($_GET['id'])) {
    $modelId = $_GET['id'];
    $model = $db->read('models', ['id' => $modelId]);

    if (empty($model)) {
        echo "<p>Model not found.</p>";
        exit;
    }
    $model = $model[0];
} else {
    echo "<p>No model specified.</p>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelId = $_POST['model_id'];
    $name = $db->sanitize($_POST['name']);
    $year = (int)$_POST['year'];
    $price = (float)$_POST['price'];
    $description = $db->sanitize($_POST['description']);

    // Fetch the current model details
    $model = $db->read('models', ['id' => $modelId]);
    if ($model) {
        $image_path = $model[0]['image']; // Retain existing image

        // Handle image upload if a new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../public/img/";
            $image_name = basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $image_name;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate the image file
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size (limit to 2MB)
            if ($_FILES["image"]["size"] > 2000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow only specific file formats
            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowed_types)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Attempt to upload the file if validations passed
            if ($uploadOk && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file; // Update image path
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        // Update model details in the database
        $updateSuccess = $db->update('models', [
            'name' => $name,
            'year' => $year,
            'price' => $price,
            'description' => $description,
            'image' => $image_path
        ], ['id' => $modelId]);

        if ($updateSuccess) {
            echo "<p>Model updated successfully!</p>";
            header("Location: ./view_models.php");
            exit;
        } else {
            echo "<p>Failed to update model.</p>";
        }
    } else {
        echo "<p>Model not found.</p>";
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Model</title>
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <nav>
        <div class="navLeft">
            <ul>
                <li class="logo"><a href="./admin_dashboard.php">Dashboard</a></li>
                <li><a href="./manage_users.php">Manage Users</a></li>
                <li><a href="./manage_orders.php">Manage Orders</a></li>
            </ul>
        </div>
        <div class="navRight">
            <a href="./logout.php">Logout</a>
        </div>
    </nav>
    <main>
        <h1>Edit Model</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="model_id" value="<?php echo htmlspecialchars($model['id']); ?>">
            <label for="name">Model Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($model['name']); ?>" required>
            
            <label for="year">Year:</label>
            <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($model['year']); ?>" required>
            
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($model['price']); ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($model['description']); ?></textarea>
            
            <label for="image">Upload New Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <button type="submit">Update Model</button>
        </form>


    </main>
</body>
</html>
