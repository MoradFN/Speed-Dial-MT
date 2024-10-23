<?php
// /src/models/HistoryAccountInteractionModel.php
class HistoryAccountInteractionModel {
    private $db;
    private $contactInteractionModel;

    public function __construct($db) {
        $this->db = $db;
    }
    public function setContactInteractionModel($contactInteractionModel) {
        $this->contactInteractionModel = $contactInteractionModel;
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



////////////////////////////////WORK IN PROGRESS//////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

        // Fetch all interactions for all accounts med join från accounts för name.
public function getAllAccountInteractions() {
    $sql = "SELECT hai.*, a.name AS account_name 
            FROM history_account_interaction hai 
            JOIN accounts a ON hai.account_id = a.id";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
}

////////COMBINERAD FRÅN HISTORY CONTACT INTERACTION MODEL///////////////

public function getAllAccountInteractionsWithContacts() {
    // Step 1: Fetch all account interactions with account name
    $accountInteractions = $this->getAllAccountInteractions();

    // Step 2: Loop through each account interaction and fetch related contact interaction
    foreach ($accountInteractions as &$accountInteraction) {
        if (!empty($accountInteraction['related_contact_interaction_id'])) {
            $relatedContactInteraction = $this->contactInteractionModel->getContactInteractionById($accountInteraction['related_contact_interaction_id']);
            $accountInteraction['related_contact_interaction'] = $relatedContactInteraction;
        } else {
            $accountInteraction['related_contact_interaction'] = null;
        }
    }

    return $accountInteractions;
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////


// Fetch a specific interaction by ID (if needed)
public function getAccountInteractionById($accountInteractionId) {
    $sql = "SELECT * FROM history_account_interaction WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }

    $stmt->bind_param('i', $accountInteractionId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // Return a single row as an associative array
}




// public function getAllAccountInteractionsWithContacts() {
//     $sql = "SELECT * FROM history_account_interaction";
//     $stmt = $this->db->prepare($sql);
//     if (!$stmt) {
//         die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
//     }

//     $stmt->execute();
//     $result = $stmt->get_result();

//     $accountInteractions = $result->fetch_all(MYSQLI_ASSOC);

//     // For each account interaction, fetch the related contact interaction
//     foreach ($accountInteractions as &$accountInteraction) {
//         if (!empty($accountInteraction['related_contact_interaction_id'])) {
//             // Assuming $this->contactModel is already defined in your class
//             $relatedContactInteraction = $this->contactModel->getInteractionById($accountInteraction['related_contact_interaction_id']);
//             $accountInteraction['related_contact_interaction'] = $relatedContactInteraction;
//         }
//     }

//     return $accountInteractions;
// }



//// KULIS HISTORY UNDER

// // Fetch all interactions by account ID
// public function getInteractionsByAccountId($accountId) {
//     // Debugging the account ID before executing the query

//     $sql = "SELECT * FROM history_account_interaction WHERE account_id = ?";
//     $stmt = $this->db->prepare($sql);
//     if (!$stmt) {
//         die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
//     }

//     $stmt->bind_param('i', $accountId); // 'i' stands for integer binding
//     $stmt->execute();
    
//     $result = $stmt->get_result();

//     return $result->fetch_all(MYSQLI_ASSOC); // Return all rows as an associative array
// }
// public function getInteractionsByAccount() {
//     // Debugging the account ID before executing the query


//     $sql = "SELECT hai.*, c.first_name, c.last_name, a.name,
//     hci.id AS hci_id,	
//     hci.contact_id AS hci_contact_id,	
//     hci.user_id AS hci_user_id,	
//     hci.target_list_id AS hci_target_list_id,	
//     hci.outcome AS hci_outcome,	
//     hci.notes AS hci_notes,	
//     hci.contact_method AS hci_contact_method,	
//     hci.next_contact_date AS hci_next_contact_date,	
//     hci.contacted_at AS hci_contacted_at,	
//     hci.interaction_duration AS hci_interaction_duration,	
//     hci.created_at AS hci_created_at,	
//     hci.updated_at AS hci_updated_at  FROM history_account_interaction as hai
//         INNER JOIN history_contact_interaction AS hci  ON hci.id = hai.related_contact_interaction_id
//         INNER JOIN accounts as a ON a.id = hai.account_id
//         INNER JOIN contacts as c ON c.id = hci.contact_id;";
//     $stmt = $this->db->prepare($sql);
//     if (!$stmt) {
//         die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
//     }
//     $stmt->execute();
//     $result = $stmt->get_result();

//     return $result->fetch_all(MYSQLI_ASSOC); // Return all rows as an associative array
// }
//// KULIS HISTORY ABOVE

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
