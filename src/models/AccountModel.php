<?php
// /src/models/AccountModel.php

class AccountModel {
    private $db;

    public function __construct($db) {
        $this->db = $db; 
    }

    // Fetch all accounts from the database
    public function getAllAccounts() {
        $sql = "SELECT * FROM accounts"; 
        $result = $this->db->query($sql);
        // Check if query was successful
        if ($result === false) {
            die("Database query error: " . $this->db->error);
        }
        // Fetch all results as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch a single account by ID
    public function getAccountById($id) {
        $sql = "SELECT * FROM accounts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);  // "i" means the parameter is an integer
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Fetch accounts by a list of account IDs
    public function getAccountsByIds($accountIds) {
        $placeholders = implode(',', array_fill(0, count($accountIds), '?'));
        $sql = "SELECT id AS account_id, name AS account_name, address, city, state, postal_code, 
                       country, phone AS account_phone, email AS account_email, website, industry
                FROM accounts
                WHERE id IN ($placeholders)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        $types = str_repeat('i', count($accountIds));
        $stmt->bind_param($types, ...$accountIds);

        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

        // Insert a new account
        public function createAccount($name, $email) {
            $sql = "INSERT INTO accounts (name, email) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                die("Database prepare error: " . $this->db->error);
            }
            $stmt->bind_param("ss", $name, $email);  // "ss" means both parameters are strings
            return $stmt->execute();
        }

}
