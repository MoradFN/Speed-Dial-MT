<?php
class AccountInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Log an interaction for an account
    public function logAccountInteraction($accountId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate = null) {
        $sql = "INSERT INTO account_interaction_history (account_id, user_id, notes, outcome, contact_method, interaction_duration, contacted_at, next_contact_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Database error: " . $this->db->error);
        }

        $stmt->bind_param('iisssiss', $accountId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);
        return $stmt->execute();
    }

    // Fetch the interaction history for a specific account
    public function getInteractionHistoryByAccountId($accountId) {
        $sql = "SELECT * FROM account_interaction_history WHERE account_id = ? ORDER BY contacted_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $accountId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
