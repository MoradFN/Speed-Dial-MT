<?php
class ContactInteractionHistoryModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Insert new interaction record for contact
    public function insertInteraction($contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
        $sql = "INSERT INTO contact_interaction_history (contact_id, user_id, target_list_id, next_contact_date, notes, outcome, contact_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }

        $stmt->bind_param('iiissss', $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod);
        return $stmt->execute();
    }

    // Fetch interaction history for a contact
    public function getInteractionHistoryByContactId($contactId) {
        $sql = "SELECT * FROM contact_interaction_history WHERE contact_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $contactId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Update interaction (for example, log next contact date)
    public function updateNextContactDate($interactionId, $nextContactDate) {
        $sql = "UPDATE contact_interaction_history SET next_contact_date = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $nextContactDate, $interactionId);
        return $stmt->execute();
    }
}
