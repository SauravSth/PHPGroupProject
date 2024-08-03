<?php
require_once 'db.php';

$db = new Database();

// Initialize query and conditions array
$query = "SELECT * FROM models WHERE 1=1";
$conditions = [];
$params = [];  // Initialize params array

// Filter by make
if (!empty($_GET['make_id'])) {
    $make_id = (int)$_GET['make_id'];
    $conditions[] = "make_id = ?";
    $params[] = $make_id;
}

// Filter by year
if (!empty($_GET['year'])) {
    $year = (int)$_GET['year'];
    $conditions[] = "year = ?";
    $params[] = $year;
}

// Filter by price range
if (!empty($_GET['price_min'])) {
    $price_min = (float)$_GET['price_min'];
    $conditions[] = "price >= ?";
    $params[] = $price_min;
}
if (!empty($_GET['price_max'])) {
    $price_max = (float)$_GET['price_max'];
    $conditions[] = "price <= ?";
    $params[] = $price_max;
}

// Filter by color
if (!empty($_GET['color'])) {
    $color = $db->sanitize($_GET['color']);
    $conditions[] = "color LIKE ?";
    $params[] = "%$color%";
}

// Filter by model name
if (!empty($_GET['model'])) {
    $model = $db->sanitize($_GET['model']);
    $conditions[] = "name LIKE ?";
    $params[] = "%$model%";
}

// Combine conditions into the query
if (count($conditions) > 0) {
    $query .= " AND " . implode(" AND ", $conditions);
}

// Execute the query using the query method
$cars = $db->query($query, $params);

if ($cars) {
    foreach ($cars as $car) {
        echo "<div>";
        echo "<h2>" . htmlspecialchars($car['name']) . " (" . htmlspecialchars($car['year']) . ")</h2>";
        echo "<p>Price: $" . htmlspecialchars($car['price']) . "</p>";
        echo "<p>Description: " . htmlspecialchars($car['description']) . "</p>";
        echo "<p>Color: " . htmlspecialchars($car['color']) . "</p>";
        echo "<img src='../public/img" . htmlspecialchars($car['image']) . "' alt='" . htmlspecialchars($car['name']) . "' style='width:300px; height:auto;'>";
        echo "</div>";
        echo "<hr>";
    }
} else {
    echo "No cars match your search criteria.";
}

?>
