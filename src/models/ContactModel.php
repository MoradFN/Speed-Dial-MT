<?php
// /src/models/ContactModel.php
class ContactModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
/////////////test/////////////
 // Fetch contacts by an array of contact IDs
 public function getContactsByIds(array $contactIds) {
    if (empty($contactIds)) {
        return [];
    }

    // Prepare the placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($contactIds), '?'));
    $types = str_repeat('i', count($contactIds)); // Assuming all IDs are integers

    $sql = "SELECT id AS contact_id, first_name, last_name, phone AS contact_phone, email AS contact_email, 
                   mobile_phone, job_title, status AS contact_status, notes
            FROM contacts
            WHERE id IN ($placeholders)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param($types, ...$contactIds);

    if (!$stmt->execute()) {
        die('Execute failed: (' . $stmt->errno . ') ' . $stmt->error);
    }

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

    /////////////////////////////////



// Get contacts by account ID
public function getContactsByAccountId($accountId) {
    $sql = "SELECT id AS contact_id, first_name, last_name, phone AS contact_phone, email AS contact_email, 
                   mobile_phone, job_title, status AS contact_status, notes
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
