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

////////////////////////////////WORK IN PROGRESS(WORKING)/////////////////////////////////////////
//////////////////////////////////////USED IN ENHANCED HISTORY////////////////////////////////////

 // Fetch a specific contact interaction by ID med JOIN från contacts table för firstname och lastname.
public function getContactInteractionById($interactionId) {
    $sql = "SELECT hci.*, c.first_name, c.last_name 
            FROM history_contact_interaction hci 
            JOIN contacts c ON hci.contact_id = c.id 
            WHERE hci.id = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }

    $stmt->bind_param('i', $interactionId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}
//////////////////////////////////////////////////////////////////////////////////////////////////

// new enhanced.

public function getRelatedContactsByInteractionIds(array $interactionIds) {
    if (empty($interactionIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($interactionIds), '?'));
    $types = str_repeat('i', count($interactionIds));

    $sql = "SELECT hci.id, hci.contact_id, hci.outcome, hci.notes, 
                   hci.contact_method, hci.next_contact_date, 
                   CONCAT(c.first_name, ' ', c.last_name) AS contact_name
            FROM history_contact_interaction hci
            JOIN contacts c ON hci.contact_id = c.id
            WHERE hci.id IN ($placeholders)";

    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }

    $stmt->bind_param($types, ...$interactionIds);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return an associative array keyed by interaction ID for easy mapping
    $contactDetails = [];
    while ($row = $result->fetch_assoc()) {
        $contactDetails[$row['id']] = $row;
    }

    return $contactDetails;
}


//////////////////////////////////////////////////////////////////////////////////////////////////
    

    // // Update interaction (for example, log next contact date)
    // public function updateNextContactDate($interactionId, $nextContactDate) {
    //     $sql = "UPDATE history_contact_interaction SET next_contact_date = ? WHERE id = ?";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param('si', $nextContactDate, $interactionId);
    //     return $stmt->execute();
    // }
}
