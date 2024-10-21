<?php
// /src/models/HistoryContactInteractionModel.php
class HistoryContactInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Insert new interaction record for contact
    public function insertInteraction($contactId, $userId, $targetListId, $outcome, $nextContactDate = null, $notes = null, $contactMethod = null) {
        $sql = "INSERT INTO history_contact_interaction (contact_id, user_id, target_list_id, next_contact_date, notes, outcome, contact_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
    
        // Prepare the statement
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
        }
    
        // Ensure optional fields are properly handled
        $nextContactDate = $nextContactDate ?? null;
        $notes = $notes ?? null;
        $contactMethod = $contactMethod ?? null;
    
        // Bind the parameters
        $stmt->bind_param(
            'iiissss', 
            $contactId, 
            $userId, 
            $targetListId, 
            $outcome, 
            $nextContactDate, 
            $notes,
            $contactMethod
        );
    
        return $stmt->execute();
    }
    
    // // Fetch interaction history for a contact
    // public function getInteractionHistoryByContactId($contactId) {
    //     $sql = "SELECT * FROM history_contact_interaction WHERE contact_id = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('i', $contactId);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    // }

    // // Update interaction (for example, log next contact date)
    // public function updateNextContactDate($interactionId, $nextContactDate) {
    //     $sql = "UPDATE history_contact_interaction SET next_contact_date = ? WHERE id = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('si', $nextContactDate, $interactionId);
    //     return $stmt->execute();
    // }
}
