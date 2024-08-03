<?php
// db_conn.php

class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "cars";
    private $conn;

    public function __construct() {
        $this->secureSession();
        session_start();  // Start session
        $this->connectDB();
    }

    private function connectDB() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            error_log("Connection failed: " . $this->conn->connect_error);  // Log the error
            throw new Exception("Database connection failed");  // Throw an exception
        }
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
            // Dynamically generate the types string based on the number of parameters
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
        error_log("Prepare failed: " . $this->conn->error);  // Log prepare error
        return false;
    }
    $types = '';
    $params = [];
    if (!empty($conditions)) {
        $types .= str_repeat('s', count($conditions));
        $params = array_values($conditions);
    }
    if ($limit !== null) {
        $types .= 'i';  // Add integer type for limit
        $params[] = $limit;  // Add limit to parameters
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


// Usage Example
// $db = new Database();
// $db->create('table_name', ['column1', 'column2'], ['value1', 'value2']);
// $result = $db->read('table_name', ['column1' => 'value1'])


