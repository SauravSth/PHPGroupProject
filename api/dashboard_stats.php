<?php
// Include database connection and Cart classes
include './db_queries/db.php';

// Create an instance of the Database class
$db = new Database();

header('Content-Type: application/json');

// Initialize response array
$response = [];

// Fetch total makes
$sql = "SELECT COUNT(*) as total FROM Makes";
$response['makes'] = fetchStat($db, $sql);

// Fetch total models
$sql = "SELECT COUNT(*) as total FROM Models";
$response['models'] = fetchStat($db, $sql);

// Fetch total users
$sql = "SELECT COUNT(*) as total FROM Users";
$response['users'] = fetchStat($db, $sql);

// Fetch total orders
$sql = "SELECT COUNT(*) as total FROM Orders";
$response['orders'] = fetchStat($db, $sql);

// Fetch orders over time for chart
$sql = "SELECT DATE(date) as date, COUNT(*) as total FROM Orders GROUP BY DATE(date)";
$result = $db->query($sql);
$dates = [];
$ordersOverTime = [];
if ($result) {
    foreach ($result as $row) {
        $dates[] = $row['date'];
        $ordersOverTime[] = $row['total'];
    }
}
$response['dates'] = $dates;
$response['ordersOverTime'] = $ordersOverTime;

// Fetch recent orders for table
$sql = "SELECT order_id, customer_name, DATE(date) as date, total FROM Orders ORDER BY date DESC LIMIT 5";
$result = $db->query($sql);
$recentOrders = [];
if ($result) {
    foreach ($result as $row) {
        $recentOrders[] = $row;
    }
}
$response['recentOrders'] = $recentOrders;

// Example API request using the apiRequest function
$apiUrl = 'https://example.com/api/some-endpoint'; // Replace with actual API URL
$apiResponse = $db->apiRequest($apiUrl);
$response['apiData'] = $apiResponse;

// Function to fetch statistics from the database
function fetchStat($db, $sql) {
    $result = $db->query($sql);
    return $result ? $result[0]['total'] : 0;
}

// Close database connection
unset($db);

// Return JSON response
echo json_encode($response);
?>
