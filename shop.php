<?php
require_once './db_queries/db.php';

$db = new Database();

// Initialize the base query and conditions array
$query = "SELECT * FROM models WHERE 1=1";
$conditions = [];
$params = [];

// Check if the form is submitted with any filter criteria
if (!empty($_GET)) {
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
}

// Execute the query using the query method
$models = $db->query($query, $params);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="./public/css/reset.css" />
		<link rel="stylesheet" href="./public/css/styles.css" />
		<title>Document</title>
	</head>
	<body>
				<?php include './nav.php' ?>

		<header id="shopHeader">
			<form method="GET" action="shop.php">
				<div class="searchBar">
					<div class="icon">
						<img src="./public/img/icons/search-svgrepo-com.svg" alt="" />
					</div>
					<input
						type="text"
						name="model"
						id="model"
						placeholder="Search model"
					/>
				</div>
				<div class="shopFormAlt">
					<div class="carMake">
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
						</select>
					</div>
					<div class="carYear">
						<label for="year">Year:</label>
						<input type="number" name="year" id="year" placeholder="e.g., 2020">
					</div>
					<div class="carPriceRange">
						<label for="price_min">Price Range:</label>
						<div class="priceInput">
							<input type="number" name="price_min" id="price_min" placeholder="Min"> 
							<input type="number" name="price_max" id="price_max" placeholder="Max">
						</div>
					</div>
				</div>
				<input type="submit" value="Search">
			</form>
		</header>
		<main id="shopMain">
			<div id="shopMainWrapper">
				<?php
					if (!empty($models)){
					foreach ($models as $model) {
						$make = $db->read('makes', ['id' => $model['make_id']])[0]['name'];
						echo '<a href="details.php?id=' . $model['id'] . '" class="carCard">'; 
						echo '  <div class="cardHeader">';
						echo '      <img src="./public/img' . htmlspecialchars($model['image']) . '" alt="' . htmlspecialchars($model['name']) . '"/>';
						echo '  </div>';
						echo '  <div class="cardBody">';
						echo '      <p class="carTitle"><strong>' . htmlspecialchars($make) . ' ' . htmlspecialchars($model['name']) . '</strong></p>';
						echo '      <p class="carYear">Year: ' . $model['year'] . '</p>';
						echo '      <p class="carPrice">$' . number_format($model['price'], 2) . '</p>';
						echo '  </div>';

						echo '</a>'; 
					}
					}
					?>

			</div>
		</main>
				<?php include './footer.php' ?>

	</body>
</html>
