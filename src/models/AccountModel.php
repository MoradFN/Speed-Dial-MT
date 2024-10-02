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


    // Get accounts by target list ID
    public function getAccountsByTargetList($targetListId) {
        $sql = "SELECT a.id AS account_id, a.name AS account_name, a.address, a.city, a.state, a.postal_code, 
                       a.country, a.phone AS account_phone, a.email AS account_email, a.website, a.industry
                FROM target_list_account_relation tlar
                INNER JOIN accounts a ON tlar.account_id = a.id
                WHERE tlar.target_list_id = ?";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        $stmt->bind_param('i', $targetListId);
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
