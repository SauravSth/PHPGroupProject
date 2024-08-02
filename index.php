<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./public/css/styles.css" rel="stylesheet" />
        <title>Document</title>
        </head>
        <body>
                <h1>Car Models</h1>

    <!-- Search Form -->
   <form method="GET" action="./db_queries/search_cars.php">
    <label for="make">Car Make:</label>
    <select name="make_id" id="make">
        <option value="">All Makes</option>
        <?php
        require_once './db_queries/db.php';
        $db = new Database();
        $makes = $db->read('makes');
        foreach ($makes as $make) {
            echo "<option value='" . htmlspecialchars($make['id']) . "'>" . htmlspecialchars($make['name']) . "</option>";
        }
        ?>
    </select><br>

    <label for="year">Year:</label>
    <input type="number" name="year" id="year" placeholder="e.g., 2020"><br>

    <label for="price_min">Price Range:</label>
    <input type="number" name="price_min" id="price_min" placeholder="Min"> - 
    <input type="number" name="price_max" id="price_max" placeholder="Max"><br>

    <label for="color">Color:</label>
    <input type="text" name="color" id="color" placeholder="e.g., Red"><br>

    <!-- New search input for car model name -->
    <label for="model">Car Model:</label>
    <input type="text" name="model" id="model" placeholder="e.g., Civic"><br>


    <input type="submit" value="Search">
</form>
           <?php
// Include the database connection class
// include './db_queries/db.php';

// Instantiate the Database class
$db = new Database();

// Fetch all the models data
$models = $db->read('models');

// Check if data exists
if (!empty($models)) {
    echo "<div class='container'>";
    echo "<table border='1'>";
    echo "<tr>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
          </tr>";

    // Display each row
    foreach ($models as $model) {
        // Fetch the make name using the make_id
        $make = $db->read('makes', ['id' => $model['make_id']])[0]['name'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($make) . "</td>";
        echo "<td>" . htmlspecialchars($model['name']) . "</td>";
        echo "<td>" . htmlspecialchars($model['year']) . "</td>";
        echo "<td>" . htmlspecialchars($model['price']) . "</td>";
        echo "<td>" . htmlspecialchars($model['description']) . "</td>";
        echo "<td><img src='./public/img" . htmlspecialchars($model['image']) . "' alt='" . htmlspecialchars($model['name']) . "' style='width:100px;'></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "<div class='container'><p>No models found.</p></div>";
}
?>

</body>
</html>