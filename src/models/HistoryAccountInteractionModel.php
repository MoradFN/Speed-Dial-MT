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



////////////////////////////////WORK IN PROGRESS(WORKING)/////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

        // Fetch all interactions for all accounts med join från accounts för name.
        /// TEMPORARILY COMMENTED OUT. <-----------------------------------------------------

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
//////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////WORK IN PROGRESS ENHANCED HISTORY
// Fetch all interactions with account, contact, target list, and campaign details

///NEW FLAT MODEL FOR INTERACTION HISTORY , STANDARD -LANDING

public function getDetailedInteractionHistory($filters, $orderBy, $direction, $page, $limit) { //MTTODO - if page is not set, default to 1, if limit is not set, default to 10 when querying for pagination. Othetwise fetches all. FLYTTAD TILL SERVICES.

    // Handle null ordering for `contact_next_contact_date`
    $orderByClause = $orderBy === 'contact_next_contact_date'
        ? "CASE WHEN hci.next_contact_date IS NULL THEN 1 ELSE 0 END, hci.next_contact_date $direction"
        : "$orderBy $direction";

    // Prepare the SQL query with dynamic WHERE conditions
    $whereClauses = [];
    $params = [];
    $types = '';

    // Add filters to WHERE clauses (e.g., filtering by account name, campaign, user, etc.)
    $filtersMap = [
        'user_name' => ['u.username LIKE ?', 's'],

        'campaign_name' => ['cmp.name LIKE ?', 's'],
        'campaign_status' => ['cmp.status = ?', 's'],
        'campaign_start_date' => ['cmp.start_date = ?', 's'],
        'campaign_end_date' => ['cmp.end_date = ?', 's'],
        'campaign_description' => ['cmp.description LIKE ?', 's'],

        'target_list_name' => ['t.name LIKE ?', 's'],
        'target_list_description' => ['t.description LIKE ?', 's'],

        'account_name' => ['a.name LIKE ?', 's'],

        'contact_name' => ["CONCAT(c.first_name, ' ', c.last_name) LIKE ?", 's'],
        'contact_interaction_outcome' => ['hci.outcome = ?', 's'],
        'contact_phone' => ['c.phone LIKE ?', 's'],
        'contact_notes' => ['hci.notes LIKE ?', 's'],
        'contact_contacted_at' => ['hci.contacted_at = ?', 's'],
        'contact_next_contact_date' => ['hci.next_contact_date = ?', 's'],
        'contact_interaction_duration' => ['hci.interaction_duration = ?', 's']
    ];

    // Add filters from the filters map to the WHERE clauses // Sök funktion, lägg till ''wildcard'' för att söka med ''LIKE' ist för hela namnet.
    foreach ($filtersMap as $key => [$clause, $type]) {
        if (isset($filters[$key]) && $filters[$key] !== null && $filters[$key] !== '') { // Tillåter 0.
            $whereClauses[] = $clause;
            $params[] = $key === 'user_name' || $key === 'campaign_name' || $key === 'target_list_name' || $key === 'account_name' || $key === 'contact_name' || $key === 'contact_phone' || $key === 'contact_notes' ? '%' . $filters[$key] . '%' : $filters[$key];
            $types .= $type;
        }
    }



    // Date Range Filter (optional 'from' and 'to' dates)
    if (isset($filters['date_field']) && in_array($filters['date_field'], ['contact_contacted_at', 'contact_next_contact_date'])) {
        // Define the correct alias field
        $dateField = $filters['date_field'] === 'contact_contacted_at' ? 'hci.contacted_at' : 'hci.next_contact_date';
    
        if (!empty($filters['date_from'])) {
            $whereClauses[] = $dateField . ' >= ?';
            $params[] = $filters['date_from'];
            $types .= 's';
        }
        if (!empty($filters['date_to'])) {
            // Set the end date time to 23:59:59 to include the entire end date
            $endDateTime = $filters['date_to'] . ' 23:59:59'; //MTTODO - Detta är för att få med det sista sdatumet man sökt till. ex: om man söker till '2024-10-30' excluderas den ej. // common aproach egentliger att add 1 day?
            $whereClauses[] = $dateField . ' <= ?';
            $params[] = $endDateTime;
            $types .= 's';
        }
    }

    // Build the WHERE SQL string
    $whereSql = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
    
        // Total Count Query for pagination
        $countSql = "SELECT COUNT(*) as total
        FROM history_account_interaction hai
        JOIN accounts a ON hai.account_id = a.id
        JOIN users u ON hai.user_id = u.id
        LEFT JOIN target_lists t ON hai.target_list_id = t.id
        LEFT JOIN campaigns cmp ON t.campaign_id = cmp.id
        LEFT JOIN history_contact_interaction hci ON hci.id = hai.related_contact_interaction_id
        LEFT JOIN contacts c ON hci.contact_id = c.id
        $whereSql";

        $countStmt = $this->db->prepare($countSql);
        if (!empty($params)) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $totalRecords = $countStmt->get_result()->fetch_assoc()['total'];

        // Calculate offset
        $offset = ($page - 1) * $limit;

    // Prepare the final SQL query with filters and ordering
    $sql = "SELECT hai.*, 
                   a.name AS account_name, 
                   CONCAT(c.first_name, ' ', c.last_name) AS contact_name, 
                   t.name AS target_list_name, 
                   t.description AS target_list_description, 
                   u.username AS user_name,
                   cmp.name AS campaign_name,
                   cmp.description AS campaign_description,
                   cmp.start_date AS campaign_start_date,
                   cmp.end_date AS campaign_end_date,
                   cmp.status AS campaign_status,
                   hci.outcome AS contact_interaction_outcome,
                   c.phone AS contact_phone,
                   hci.notes AS contact_notes,
                   hci.contacted_at AS contact_contacted_at,
                   hci.next_contact_date AS contact_next_contact_date,
                   hci.interaction_duration AS contact_interaction_duration
            FROM history_account_interaction hai
            JOIN accounts a ON hai.account_id = a.id
            JOIN users u ON hai.user_id = u.id
            LEFT JOIN target_lists t ON hai.target_list_id = t.id
            LEFT JOIN target_list_account_relation tlr ON tlr.account_id = hai.account_id AND tlr.target_list_id = t.id
            LEFT JOIN campaigns cmp ON t.campaign_id = cmp.id
            LEFT JOIN history_contact_interaction hci ON hci.id = hai.related_contact_interaction_id
            LEFT JOIN contacts c ON hci.contact_id = c.id
            $whereSql
            ORDER BY $orderByClause  -- original: $orderBy $direction, butt för att hantera null sist vid asc OCH desc.
            LIMIT ? OFFSET ?";

    // Bind limit and offset
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';

    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: (' . $this->db->errno . ') ' . $this->db->error);
    }

    // Bind parameters dynamically based on filters
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

       return [
        'data' => $result->fetch_all(MYSQLI_ASSOC),
        'total_records' => $totalRecords,
        'page' => $page,
        'limit' => $limit,
        'total_pages' => ceil($totalRecords / $limit)
    ];
}






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
