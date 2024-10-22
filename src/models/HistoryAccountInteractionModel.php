<?php
// /src/models/HistoryAccountInteractionModel.php
class HistoryAccountInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

        // Insert a new interaction for an account
        public function insertInteraction($accountId, $userId, $targetListId, $relatedContactInteractionId = null, $outcome = null, $notes = null, $contactMethod = null, $nextContactDate = null) { 
            $sql = "INSERT INTO history_account_interaction (account_id, user_id, target_list_id, related_contact_interaction_id, outcome, notes, contact_method,  next_contact_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Ensure variables for bind_param (cannot pass null directly)
            $relatedContactInteractionId = $relatedContactInteractionId ?? null;
            $outcome = $outcome ?? null;
            $notes = $notes ?? null;
            $contactMethod = $contactMethod ?? null;
            $nextContactDate = $nextContactDate ?? null;
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                'iiiissss', 
                $accountId, 
                $userId, 
                $targetListId, 
                $relatedContactInteractionId,
                $outcome, 
                $notes, 
                $contactMethod,
                $nextContactDate
            );
            $stmt->execute();
        }

    // // Get interaction history by account ID
    // public function getInteractionHistoryByAccountId($accountId) {
    //     $sql = "SELECT * FROM history_account_interaction WHERE account_id = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('i', $accountId);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // }

    // // Get related contacts by account ID
    // public function getRelatedContactsByAccountId($accountId) {
    //     $sql = "SELECT * FROM contacts WHERE account_id = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('i', $accountId);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // }



    // // Update next contact date based on a contact ID
    // public function updateNextContactDateByContact($contactId, $nextContactDate) {
    //     $sql = "UPDATE history_account_interaction
    //             SET next_contact_date = ?
    //             WHERE account_id = (SELECT account_id FROM contacts WHERE id = ?)";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('si', $nextContactDate, $contactId);
    //     $stmt->execute();
    // }


    
    
    
    
}
