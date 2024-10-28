<?php
// /src/models/AccountContactRelationModel.php

class AccountContactRelationModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Fetach all account_ids for a given contact from the relation table [Passed to ContactModel? not any more]MTTODO- Check
    public function getAccountIdByContactId($contactId) {
        $sql = "SELECT account_id FROM account_contact_relation WHERE contact_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $contactId);

        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }

        $result = $stmt->get_result()->fetch_assoc();
        return $result['account_id'] ?? null;
    }


    // Fetch all contact_ids for a given account from the relation table
    public function getContactIdsByAccountId($accountId) {
        $sql = "SELECT contact_id FROM account_contact_relation WHERE account_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $accountId);

        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Insert a new account-contact relation
    public function insertAccountContactRelation($accountId, $contactId) {
        $sql = "INSERT INTO account_contact_relation (account_id, contact_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }

        $stmt->bind_param('ii', $accountId, $contactId);
        
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }

    // Delete a relation between an account and a contact
    public function deleteAccountContactRelation($accountId, $contactId) {
        $sql = "DELETE FROM account_contact_relation WHERE account_id = ? AND contact_id = ?";
        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }

        $stmt->bind_param('ii', $accountId, $contactId);
        
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }

        return $stmt->affected_rows > 0;
    }
}
