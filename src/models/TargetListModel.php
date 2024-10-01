<?php
// src/models/TargetListModel.php

class TargetListModel {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Dependency injection of the Database object
    }

    // Fetch all target lists (with optional filtering by user, campaign, or status)
    public function getAllTargetLists($filters = []) {
        $sql = "SELECT tl.*, c.name AS campaign_name
                FROM target_lists tl
                LEFT JOIN campaigns c ON tl.campaign_id = c.id
                WHERE 1=1"; // Base query

        // Apply filters if provided
        $params = [];
        if (isset($filters['assigned_to'])) {
            $sql .= " AND tl.assigned_to = ?";
            $params[] = $filters['assigned_to'];
        }
        if (isset($filters['campaign_id'])) {
            $sql .= " AND tl.campaign_id = ?";
            $params[] = $filters['campaign_id'];
        }
        if (isset($filters['status'])) {
            $sql .= " AND tl.status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single target list by ID
    public function getTargetListById($targetListId) {
        $sql = "SELECT * FROM target_lists WHERE id = ?";
        $stmt = $this->db->query($sql, [$targetListId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch accounts and their related contacts for a specific target list
    public function getAccountsAndContactsByTargetList($targetListId) {
        $sql = "SELECT a.*, c.first_name, c.last_name, c.phone, c.email, c.status AS contact_status
                FROM target_list_relation tlr
                INNER JOIN accounts a ON tlr.account_id = a.id
                INNER JOIN contacts c ON a.id = c.account_id
                WHERE tlr.target_list_id = ?";
        $stmt = $this->db->query($sql, [$targetListId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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
