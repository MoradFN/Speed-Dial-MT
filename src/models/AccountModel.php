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

    // Combined Filters: Combine multiple filters to create more advanced queries.
    public function getFilteredAccounts($filters = []) {
        $sql = "SELECT * FROM accounts WHERE 1=1"; // Start with a true condition
        $params = [];
        $types = '';

        if (isset($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
            $types .= 's'; // 's' for string
        }

        if (isset($filters['lead_source'])) {
            $sql .= " AND lead_source = ?";
            $params[] = $filters['lead_source'];
            $types .= 's';
        }

        if (isset($filters['industry'])) {
            $sql .= " AND industry = ?";
            $params[] = $filters['industry'];
            $types .= 's';
        }

        if (isset($filters['city'])) {
            $sql .= " AND city = ?";
            $params[] = $filters['city'];
            $types .= 's';
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $sql .= " AND created_at BETWEEN ? AND ?";
            $params[] = $filters['start_date'];
            $params[] = $filters['end_date'];
            $types .= 'ss';
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
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

        // Update an account by ID
    public function updateAccount($id, $name, $email, $phone, $address, $city, $state, $postal_code, $country, $website, $industry, $status, $lead_source, $notes) {
        $sql = "UPDATE accounts SET name = ?, email = ?, phone = ?, address = ?, city = ?, state = ?, postal_code = ?, country = ?, website = ?, industry = ?, status = ?, lead_source = ?, notes = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
    }
        $stmt->bind_param("sssssssssssssi", $name, $email, $phone, $address, $city, $state, $postal_code, $country, $website, $industry, $status, $lead_source, $notes, $id);
        return $stmt->execute();
        }
    // Delete an account by ID
    public function deleteAccount($id) {
        $sql = "DELETE FROM accounts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);  // "i" means the parameter is an integer
        return $stmt->execute();
    }

    // Search accounts by name, email, or status
    public function searchAccounts($searchTerm) {
        $sql = "SELECT * FROM accounts WHERE name LIKE ? OR email LIKE ? OR status LIKE ?";
        $searchTerm = '%' . $searchTerm . '%';  // Use wildcard for partial matches
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAccountsByStatus($status) {
        $sql = "SELECT * FROM accounts WHERE status = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    // Get accounts by lead source
    public function getAccountsByLeadSource($leadSource) {
        $sql = "SELECT * FROM accounts WHERE lead_source = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("s", $leadSource);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch accounts with pagination
    public function getAccountsByPage($limit, $offset) {
        $sql = "SELECT * FROM accounts LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("ii", $limit, $offset);  // Both parameters are integers
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get recently created accounts
    public function getRecentAccounts($limit) {
        $sql = "SELECT * FROM accounts ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("i", $limit);  // "i" means the parameter is an integer
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAccountsByDateRange($startDate, $endDate, $column = 'created_at') {
        $sql = "SELECT * FROM accounts WHERE $column BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        $stmt->bind_param('ss', $startDate, $endDate); // 'ss' means both parameters are strings (dates)
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
//search for accounts where the notes field contains a specific keyword
    public function searchAccountsByNotes($keyword) {
        $sql = "SELECT * FROM accounts WHERE notes LIKE ?";
        $keyword = '%' . $keyword . '%'; // Use wildcard for partial match
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        $stmt->bind_param('s', $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    

    // Get recently updated accounts - Fetch accounts that were recently created or updated.
    public function getRecentlyUpdatedAccounts($limit) {
        $sql = "SELECT * FROM accounts ORDER BY updated_at DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }
        $stmt->bind_param("i", $limit);  // "i" means the parameter is an integer
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

        // Get the total count of accounts -retrieve the total number of accounts, can add a method to count the records.
    public function countAccounts() {
        $sql = "SELECT COUNT(*) AS total FROM accounts";
        $result = $this->db->query($sql);
        if ($result === false) {
            die("Database query error: " . $this->db->error);
        }
        return $result->fetch_assoc()['total'];
    }

    // Bulk insert accounts -To handle bulk imports or multiple account creation.
    public function bulkInsertAccounts($accounts) {
        $sql = "INSERT INTO accounts (name, email, phone, address, city, state, postal_code, country, website, industry, status, lead_source, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database prepare error: " . $this->db->error);
        }

        foreach ($accounts as $account) {
            $stmt->bind_param(
                "sssssssssssss",
                $account['name'], 
                $account['email'], 
                $account['phone'], 
                $account['address'], 
                $account['city'], 
                $account['state'], 
                $account['postal_code'], 
                $account['country'], 
                $account['website'], 
                $account['industry'], 
                $account['status'], 
                $account['lead_source'], 
                $account['notes']
            );
            $stmt->execute();
        }
    }




}
