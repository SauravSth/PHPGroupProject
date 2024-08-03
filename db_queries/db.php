<?php
// db_conn.php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "cars";
    private $conn;

      public function __construct() {
        // Ensure session is started only if not already started
        if (session_status() === PHP_SESSION_NONE) {
            $this->secureSession();
            session_start();
        }
        $this->connectDB();
    }

    private function connectDB() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            error_log("Connection failed: " . $this->conn->connect_error);  // Log the error
            throw new Exception("Database connection failed");  // Throw an exception
        }
    }

   // Getter for the $conn property
    public function getConn() {
        return $this->conn;
    }

    private function secureSession() {
        // Secure session settings
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,       // true if using HTTPS
            'httponly' => true,     // prevent JavaScript access to session cookie
            'samesite' => 'Strict'  // protect against CSRF
        ]);
    }

    public function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $types = str_repeat('s', count($params));  // Assuming all parameters are strings; adjust as needed
            $stmt->bind_param($types, ...$params);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Query failed: " . $stmt->error);  // Log the error
            return false;
        }
    }

    public function create($table, $fields, $values) {
        $fieldStr = implode(',', $fields);
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $stmt = $this->conn->prepare("INSERT INTO $table ($fieldStr) VALUES ($placeholders)");

        $types = str_repeat('s', count($values));  // Assuming all are strings; adjust as needed
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Create failed: " . $stmt->error);  // Log the error
            return false;
        }
    }

    public function read($table, $conditions = []) {
        $sql = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);  // Log prepare error
            return false;
        }

        if (!empty($conditions)) {
            $types = str_repeat('s', count($conditions));
            $stmt->bind_param($types, ...array_values($conditions));
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function update($table, $data, $conditions) {
        $set = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $where = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $sql = "UPDATE $table SET $set WHERE $where";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);  // Log prepare error
            return false;
        }

        $types = str_repeat('s', count($data) + count($conditions));
        $stmt->bind_param($types, ...array_merge(array_values($data), array_values($conditions)));

        return $stmt->execute();
    }

    public function delete($table, $conditions) {
        $where = implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
        $sql = "DELETE FROM $table WHERE $where";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);  // Log prepare error
            return false;
        }

        $types = str_repeat('s', count($conditions));
        $stmt->bind_param($types, ...array_values($conditions));

        return $stmt->execute();
    }

    public function apiRequest($url, $method = 'GET', $data = []) {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'APIKEY: ' . getenv('API_KEY'),  // Use environment variable for API key
            'Content-Type: application/json',
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            error_log("Curl error: " . curl_error($curl));  // Log Curl error
        }
        curl_close($curl);

        return json_decode($result, true);
    }

    public function setSecureCookie($name, $value, $expire) {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,       // true if using HTTPS
            'httponly' => true,     // prevent JavaScript access to cookie
            'samesite' => 'Strict'  // protect against CSRF
        ]);
    }

    public function __destruct() {
        $this->conn->close();
    }
}

class Cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addItem($userId, $modelId, $quantity = 1) {
        $userId = $this->db->sanitize($userId);
        $modelId = $this->db->sanitize($modelId);
        $quantity = $this->db->sanitize($quantity);

        // Check if item is already in the cart
        $existingItem = $this->db->read('cart', ['user_id' => $userId, 'model_id' => $modelId]);

        if ($existingItem) {
            // Update quantity if item already exists
            $newQuantity = $existingItem[0]['quantity'] + $quantity;
            $this->db->update('cart', ['quantity' => $newQuantity], ['user_id' => $userId, 'model_id' => $modelId]);
        } else {
            // Add new item to the cart
            $this->db->create('cart', ['user_id', 'model_id', 'quantity'], [$userId, $modelId, $quantity]);
        }
    }

    public function removeItem($userId, $modelId) {
        $userId = $this->db->sanitize($userId);
        $modelId = $this->db->sanitize($modelId);

        $this->db->delete('cart', ['user_id' => $userId, 'model_id' => $modelId]);
    }

    public function updateItem($userId, $modelId, $quantity) {
        $userId = $this->db->sanitize($userId);
        $modelId = $this->db->sanitize($modelId);
        $quantity = $this->db->sanitize($quantity);

        $this->db->update('cart', ['quantity' => $quantity], ['user_id' => $userId, 'model_id' => $modelId]);
    }

    public function getCartItems($userId) {
        $userId = $this->db->sanitize($userId);

        return $this->db->read('cart', ['user_id' => $userId]);
    }

    public function checkout($userId) {
        $userId = $this->db->sanitize($userId);

        // Get cart items
        $cartItems = $this->getCartItems($userId);

        if (empty($cartItems)) {
            return false;  // No items in the cart
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $model = $this->db->read('models', ['id' => $item['model_id']]);
            $totalAmount += $model[0]['price'] * $item['quantity'];
        }

        // Create order
        $orderCreated = $this->db->create('orders', ['user_id', 'total_amount', 'order_status', 'payment_status'], [$userId, $totalAmount, 'pending', 'pending']);

        if ($orderCreated) {
            $orderId = $this->db->getConn()->insert_id;  // Get the last inserted order id

            // Add order items
            foreach ($cartItems as $item) {
                $model = $this->db->read('models', ['id' => $item['model_id']]);
                $this->db->create('order_items', ['order_id', 'model_id', 'quantity', 'price'], [$orderId, $item['model_id'], $item['quantity'], $model[0]['price']]);
            }

            // Clear cart
            $this->db->delete('cart', ['user_id' => $userId]);

            return true;  // Successful checkout
        }

        return false;  // Checkout failed
    }
}

// Usage Example
// $db = new Database();
// $db->create('table_name', ['column1', 'column2'], ['value1', 'value2']);
// $result = $db->read('table_name', ['column1' => 'value1'])
// $cart = new Cart();
// $cart->addItem(1, 2, 3);
// $cartItems = $cart->getCartItems(1);
// $cart->removeItem(1, 2);
// $cart->updateItem(1, 2, 5);
// $cart->checkout(1);
?>




