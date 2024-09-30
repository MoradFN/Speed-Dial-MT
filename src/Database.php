<?php

class Database {
    private $pdo;
    private $host = DB_HOST;
    private $dbname = DB_NAME;
    private $user = DB_USER;
    private $pass = DB_PASS;

    public function __construct() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass);
            // Set error reporting mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // If there’s an error connecting, terminate and display message
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Function to run SQL queries
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Database query error: " . $e->getMessage());
        }
    }

    // Optionally add more database helper functions (e.g., insert, update, etc.)
}