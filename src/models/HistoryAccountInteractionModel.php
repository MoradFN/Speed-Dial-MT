<?php
// /src/models/HistoryAccountInteractionModel.php
class HistoryAccountInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get interaction history by account ID
    public function getInteractionHistoryByAccountId($accountId) {
        $sql = "SELECT * FROM history_account_interaction WHERE account_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $accountId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get related contacts by account ID
    public function getRelatedContactsByAccountId($accountId) {
        $sql = "SELECT * FROM contacts WHERE account_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $accountId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // // Update next contact date based on a contact ID
    // public function updateNextContactDateByContact($contactId, $nextContactDate) {
    //     $sql = "UPDATE history_account_interaction
    //             SET next_contact_date = ?
    //             WHERE account_id = (SELECT account_id FROM contacts WHERE id = ?)";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('si', $nextContactDate, $contactId);
    //     $stmt->execute();
    // }

    // Insert a new interaction for an account
    public function insertInteraction($accountId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
        $sql = "INSERT INTO history_account_interaction (account_id, user_id, target_list_id, contacted_at, next_contact_date, notes, outcome, contact_method)
                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiissss', $accountId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod);
        $stmt->execute();
    }
    
    
}
