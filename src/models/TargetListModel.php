<?php
// /src/models/TargetListModel.php
// src/models/TargetListModel.php
class TargetListModel {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Fetch target list by ID
    public function getTargetListById($id) {
        $sql = "SELECT * FROM targets_list WHERE id = ?";
        return $this->db->query($sql, [$id])->fetch();
    }

    // Fetch all accounts associated with a target list
    public function getAccountsForTargetList($targetListId) {
        $sql = "SELECT accounts.* 
                FROM accounts 
                JOIN targets_list ON accounts.id = targets_list.account_id 
                WHERE targets_list.id = ?";
        return $this->db->query($sql, [$targetListId])->fetchAll();
    }

    // Fetch contacts for a specific account
    public function getContactsForAccount($accountId) {
        $sql = "SELECT * FROM contacts WHERE account_id = ?";
        return $this->db->query($sql, [$accountId])->fetchAll();
    }
}

