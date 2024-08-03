<form method="GET" action="search_cars.php">
    <label for="make">Car Make:</label>
    <select name="make_id" id="make">
        <option value="">All Makes</option>
        <?php
        require_once 'db.php';
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

    <input type="submit" value="Search">
</form>

