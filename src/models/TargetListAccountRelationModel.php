<?php
// src/models/TargetListAccountRelationModel.php

class TargetListAccountRelationModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetch account IDs related to a target list
    public function getAccountIdsByTargetListId($targetListId) {
        $sql = "SELECT account_id
                FROM target_list_account_relation
                WHERE target_list_id = ?";
        
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

    public function getActiveTargetLists() {
        $sql = "SELECT * FROM target_lists WHERE status = 'active'";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
