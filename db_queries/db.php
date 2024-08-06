<?php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "cars";
    private $conn;

      public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            $this->secureSession();
            session_start();
        }
        $this->connectDB();
    }

    private function connectDB() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            error_log("Connection failed: " . $this->conn->connect_error);  
            throw new Exception("Database connection failed");  
        }
    }

    public function getConn() {
        return $this->conn;
    }

    private function secureSession() {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    public function sanitize($data) {
        return htmlspecialchars(strip_tags($data));
    }

    public function query($sql, $params = []) {
    $stmt = $this->conn->prepare($sql);

    if ($params) {
        $types = '';
        foreach ($params as $param) {
            $types .= is_int($param) ? 'i' : (is_double($param) ? 'd' : 's');
        }
        $stmt->bind_param($types, ...$params);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        error_log("Query failed: " . $stmt->error);
        return false;
    }
}

    public function create($table, $fields, $values) {
        $fieldStr = implode(',', $fields);
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $stmt = $this->conn->prepare("INSERT INTO $table ($fieldStr) VALUES ($placeholders)");

        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Create failed: " . $stmt->error);
            return false;
        }
    }

    public function read($table, $conditions = [], $limit = null) {
    $sql = "SELECT * FROM $table";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(' AND ', array_map(fn($key) => "$key = ?", array_keys($conditions)));
    }
    if ($limit !== null) {
        $sql .= " LIMIT ?";
    }
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return false;
    }
    $types = '';
    $params = [];
    if (!empty($conditions)) {
        $types .= str_repeat('s', count($conditions));
        $params = array_values($conditions);
    }
    if ($limit !== null) {
        $types .= 'i';
        $params[] = $limit;
    }
    if ($types) {
        $stmt->bind_param($types, ...$params);
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

    public function setSecureCookie($name, $value, $expire) {
        setcookie($name, $value, [
            'expires' => $expire,
            'path' => '/',
            'domain' => $_SERVER['HTTP_HOST'],
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
    }

    public function __destruct() {
        $this->conn->close();
    }
}

class Cart {
    private $db;
    private $lastOrderId;

    public function __construct() {
        $this->db = new Database();
        $this->lastOrderId = null;
    }

    public function addItem($userId, $modelId, $quantity = 1) {
        $userId = $this->db->sanitize($userId);
        $modelId = $this->db->sanitize($modelId);
        $quantity = $this->db->sanitize($quantity);

        $existingItem = $this->db->read('cart', ['user_id' => $userId, 'model_id' => $modelId]);

        if ($existingItem) {
            $newQuantity = $existingItem[0]['quantity'] + $quantity;
            $this->db->update('cart', ['quantity' => $newQuantity], ['user_id' => $userId, 'model_id' => $modelId]);
        } else {
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
        $this->db->getConn()->begin_transaction();

        try {
            $cartItems = $this->getCartItems($userId);

            if (empty($cartItems)) {
                throw new Exception("No items in the cart"); 
            }

            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $model = $this->db->read('models', ['id' => $item['model_id']]);
                $totalAmount += $model[0]['price'] * $item['quantity'];
            }

            // Create order
            $orderCreated = $this->db->create('orders', ['user_id', 'total_amount', 'order_status', 'payment_status'], [$userId, $totalAmount, 'pending', 'pending']);

            if (!$orderCreated) {
                throw new Exception("Failed to create order");
            }

            $this->lastOrderId = $this->db->getConn()->insert_id;

            foreach ($cartItems as $item) {
                $model = $this->db->read('models', ['id' => $item['model_id']]);
                $this->db->create('order_items', ['order_id', 'model_id', 'quantity', 'price'], [$this->lastOrderId, $item['model_id'], $item['quantity'], $model[0]['price']]);
            }

            $this->db->delete('cart', ['user_id' => $userId]);

            $this->db->getConn()->commit();
            return true;
        } catch (Exception $e) {
            $this->db->getConn()->rollback();
            error_log("Checkout failed: " . $e->getMessage());
            return false;
        }
    }

    public function getLastOrderId() {
        return $this->lastOrderId;
    }
}

?>



