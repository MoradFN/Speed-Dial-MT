<?php

class ContactModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get contacts by account ID
    public function getContactsByAccountId($accountId) {
        $sql = "SELECT id AS contact_id, first_name, last_name, phone AS contact_phone, email AS contact_email, status AS contact_status
                FROM contacts
                WHERE account_id = ?";
        
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
        $stmt->bind_param('i', $accountId);
        if (!$stmt->execute()) {
            die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
        }
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
