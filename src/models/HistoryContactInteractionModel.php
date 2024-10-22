<?php
// /src/models/HistoryContactInteractionModel.php
class HistoryContactInteractionModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

// Insert new interaction record for contact
public function insertInteraction($contactId, $userId, $targetListId, $outcome, $notes = null, $contactMethod = null, $nextContactDate = null) {
    $sql = "INSERT INTO history_contact_interaction 
           (contact_id, user_id, target_list_id, outcome, notes, contact_method, next_contact_date)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
$stmt = $this->db->prepare($sql);
if (!$stmt) {
    die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
}

    // Ensure optional fields are properly handled
    $notes = !empty($notes) ? $notes : null;
    $contactMethod = $contactMethod ?? null;
    $nextContactDate = $nextContactDate ?? null;
    // Bind the parameters in the correct order
    $stmt->bind_param(
        'iiissss', 
        $contactId,      
        $userId,         
        $targetListId,    
        $outcome,         
        $notes,
        $contactMethod,   
        $nextContactDate  
    );

    // Execute and return the inserted contact interaction ID
   if ($stmt->execute()) {
    // Return the ID of the newly inserted record
    return $stmt->insert_id;
} else {
    return false; // Return false on failure
}
    
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
