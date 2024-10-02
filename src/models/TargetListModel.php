<?php
// src/models/TargetListModel.php
require_once __DIR__ . '/AccountModel.php';
require_once __DIR__ . '/ContactModel.php';
class TargetListModel {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Dependency injection of the Database object
    }
//////////////////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////        DONE       ///////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Fetch all target lists (with optional filtering by user, campaign, or status)
    public function getAllTargetLists($filters = []) {
        $sql = "SELECT tl.*, c.name AS campaign_name
                FROM target_lists tl
                LEFT JOIN campaigns c ON tl.campaign_id = c.id
                WHERE 1=1"; // Base query

        $params = [];
        $types = ''; // Used for binding parameters dynamically
        
        // Apply filters if provided
        $params = [];
        if (isset($filters['assigned_to'])) {
            $sql .= " AND tl.assigned_to = ?";
            $params[] = $filters['assigned_to'];
            $types .= 'i'; // 'i' stands for integer
        }

        if (isset($filters['campaign_id'])) {
            $sql .= " AND tl.campaign_id = ?";
            $params[] = $filters['campaign_id'];
            $types .= 'i'; // 'i' for integer
        }
        if (isset($filters['status'])) {
            $sql .= " AND tl.status = ?";
            $params[] = $filters['status'];
            $types .= 's'; // 's' for string
        }
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }

        // Bind parameters if there are any
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params); // Bind params dynamically
        }
        // Execute the query
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }
        // Get the result
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    

    // Fetch a single target list by ID
    public function getTargetListById($targetListId) {
        $sql = "SELECT * FROM target_lists WHERE id = ?";
        
        // Prepare the statement
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        // Bind the parameter (targetListId) to the query
        $stmt->bind_param('i', $targetListId); // 'i' stands for integer
        
        // Execute the query
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }
        // Get the result
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Fetch a single row as an associative array
    }

    // Fetch accounts and their related contacts for a specific target list
    public function getAccountsAndContactsByTargetList($targetListId) {
        $accountModel = new AccountModel($this->db);
        $contactModel = new ContactModel($this->db);

        // Get accounts related to the target list
        $accounts = $accountModel->getAccountsByTargetList($targetListId);
        
        // Loop through accounts and fetch their related contacts
        foreach ($accounts as &$account) {
            $account['contacts'] = $contactModel->getContactsByAccountId($account['account_id']);
        }
        return $accounts; // Return accounts with their related contacts
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Insert a new target list
    public function createTargetList($name, $description = null, $campaignId = null, $assignedTo = null) {
        $sql = "INSERT INTO target_lists (name, description, campaign_id, assigned_to) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->query($sql, [$name, $description, $campaignId, $assignedTo]);
        return $stmt->rowCount() > 0;
    }

    // Update the status of a target list
    public function updateTargetListStatus($targetListId, $status) {
        $sql = "UPDATE target_lists SET status = ? WHERE id = ?";
        $stmt = $this->db->query($sql, [$status, $targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Assign a user to a target list
    public function assignUserToTargetList($targetListId, $userId) {
        $sql = "UPDATE target_lists SET assigned_to = ? WHERE id = ?";
        $stmt = $this->db->query($sql, [$userId, $targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Lock a target list for a specific user
    public function lockTargetList($targetListId, $userId) {
        $sql = "UPDATE target_list_relation SET locked_by = ? WHERE target_list_id = ?";
        $stmt = $this->db->query($sql, [$userId, $targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Unlock a target list
    public function unlockTargetList($targetListId) {
        $sql = "UPDATE target_list_relation SET locked_by = NULL WHERE target_list_id = ?";
        $stmt = $this->db->query($sql, [$targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Delete a target list
    public function deleteTargetList($targetListId) {
        $sql = "DELETE FROM target_lists WHERE id = ?";
        $stmt = $this->db->query($sql, [$targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Fetch interaction history for a specific account
    public function getInteractionHistoryForAccount($accountId) {
        $sql = "SELECT * FROM account_interaction_history WHERE account_id = ? ORDER BY contacted_at DESC";
        $stmt = $this->db->query($sql, [$accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new interaction to the interaction history
    public function addAccountInteractionHistory($accountId, $userId, $notes, $contactedAt, $nextContactDate = null) {
        $sql = "INSERT INTO account_interaction_history (account_id, user_id, notes, contacted_at, next_contact_date)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->query($sql, [$accountId, $userId, $notes, $contactedAt, $nextContactDate]);
        return $stmt->rowCount() > 0;
    }

    // Update contact outcome or status in a target list relation
    public function updateTargetListRelationStatus($targetListId, $accountId, $status) {
        $sql = "UPDATE target_list_relation SET status = ? WHERE target_list_id = ? AND account_id = ?";
        $stmt = $this->db->query($sql, [$status, $targetListId, $accountId]);
        return $stmt->rowCount() > 0;
    }
}
