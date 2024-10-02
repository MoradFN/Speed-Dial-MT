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
        $sql = "UPDATE target_list_account_relation SET locked_by = ? WHERE target_list_id = ?";
        $stmt = $this->db->query($sql, [$userId, $targetListId]);
        return $stmt->rowCount() > 0;
    }

    // Unlock a target list
    public function unlockTargetList($targetListId) {
        $sql = "UPDATE target_list_account_relation SET locked_by = NULL WHERE target_list_id = ?";
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
        $sql = "UPDATE target_list_account_relation SET status = ? WHERE target_list_id = ? AND account_id = ?";
        $stmt = $this->db->query($sql, [$status, $targetListId, $accountId]);
        return $stmt->rowCount() > 0;
    }

    // Fetch target lists with pagination
public function getTargetListsByPage($limit, $offset) {
    $sql = "SELECT tl.*, c.name AS campaign_name
            FROM target_lists tl
            LEFT JOIN campaigns c ON tl.campaign_id = c.id
            LIMIT ? OFFSET ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('ii', $limit, $offset);  // Bind limit and offset
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Add a method for query below to filter target lists based on the completion_status.
// Fetch target lists by completion status
public function getTargetListsByCompletionStatus($completionStatus) {
    $sql = "SELECT * FROM target_lists WHERE completion_status = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $completionStatus);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


//Allow filtering target lists based on their creation or end date.
// Fetch target lists by a date range
public function getTargetListsByDateRange($startDate, $endDate) {
    $sql = "SELECT * FROM target_lists WHERE created_at BETWEEN ? AND ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);  // 'ss' stands for two strings (dates)
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Count the total number of target lists
public function countTargetLists() {
    $sql = "SELECT COUNT(*) AS total FROM target_lists";
    $result = $this->db->query($sql);
    return $result->fetch_assoc()['total'];
}

// Count target lists by status
public function countTargetListsByStatus($status) {
    $sql = "SELECT COUNT(*) AS total FROM target_lists WHERE status = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('s', $status);  // 's' stands for string (status)
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

public function getTargetListsByStatus($status) {
    // Ensure that the passed status is one of the allowed ENUM values
    $allowedStatuses = ['pending', 'active', 'completed', 'archived', 'inactive'];
    
    // Check if the status is valid
    if (!in_array($status, $allowedStatuses)) {
        throw new Exception("Invalid status provided.");
    }

    $sql = "SELECT * FROM target_lists WHERE status = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }
    
    // Bind the status to the query
    $stmt->bind_param('s', $status);  // 's' stands for string
    
    // Execute the query
    if (!$stmt->execute()) {
        die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
    }
    
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


public function getTargetListsByAssignedUser($userId) {
    $sql = "SELECT * FROM target_lists WHERE assigned_to = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}




}
