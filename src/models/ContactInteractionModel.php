<?php
class ContactInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Log an interaction for a contact
    public function logContactInteraction($contactId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate = null) {
        $sql = "INSERT INTO contact_interaction_history (contact_id, user_id, notes, outcome, contact_method, interaction_duration, contacted_at, next_contact_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database error: " . $this->db->error);
        }

        $stmt->bind_param('iisssiss', $contactId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);
        return $stmt->execute();
    }

    // Fetch the interaction history for a specific contact
    public function getInteractionHistoryByContactId($contactId) {
        $sql = "SELECT * FROM contact_interaction_history WHERE contact_id = ? ORDER BY contacted_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $contactId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
