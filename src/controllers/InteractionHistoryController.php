<?php

class InteractionHistoryController {
    private $interactionHistoryService;

    public function __construct($interactionHistoryService) {
        $this->interactionHistoryService = $interactionHistoryService;
    }


    ///HISTORY
    // Method to show interaction history with filters, sorting, and pagination // STANDARD -LANDING
    public function showInteractionHistory($orderBy = 'contact_contacted_at', $direction = 'DESC', $page = 1, $limit = 10)
    {
// Capture filter inputs from GET parameters
$filters = [
    'user_name' => $_GET['user_name'] ?? null,
    'campaign_name' => $_GET['campaign_name'] ?? null,
    'campaign_start_date' => $_GET['campaign_start_date'] ?? null,
    'campaign_end_date' => $_GET['campaign_end_date'] ?? null,
    'campaign_status' => $_GET['campaign_status'] ?? null,
    'target_list_name' => $_GET['target_list_name'] ?? null,
    'target_list_description' => $_GET['target_list_description'] ?? null,
    'account_name' => $_GET['account_name'] ?? null,
    'contact_name' => $_GET['contact_name'] ?? null,
    'contact_interaction_outcome' => $_GET['contact_interaction_outcome'] ?? null,
    'contact_phone' => $_GET['contact_phone'] ?? null,
    'contact_notes' => $_GET['contact_notes'] ?? null,
    'contact_contacted_at' => $_GET['contact_contacted_at'] ?? null,
    'contact_next_contact_date' => $_GET['contact_next_contact_date'] ?? null,
    'contact_interaction_duration' => $_GET['contact_interaction_duration'] ?? null,
    'date_field' => $_GET['date_field'] ?? null,
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null,
];
    // Retrieve interaction history data from the service layer
    $response = $this->interactionHistoryService->getInteractionHistory($filters, $orderBy, $direction, $page, $limit);
        
    // Extract data from the response for easy access in the view
    $interactionHistory = $response['data'];
    $totalPages = $response['total_pages'];
    $totalRecords = $response['total_records'];

        // Return data for rendering in the view (could be an associative array for easier access in templates)
        return [
            'interactionHistory' => $interactionHistory,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'page' => $page,
            'limit' => $limit,
            'filters' => $filters,
            'orderBy' => $orderBy,
            'direction' => $direction,
        ];
    }



/////WORK IN PROGRESS(WORKING EXEPT GET DETAILED.) /////

public function showAllInteractionHistory() {
    // Display all interactions
    $accountInteractions = $this->interactionHistoryService->getAllAccountInteractionsWithContacts();
    
    include __DIR__ . '/../views/interaction_history_account.view.php'; // View to show the full list
}
//MTTODO : show interaction history detail
public function showInteractionHistoryDetail($accountId) {
    // Display detailed view for a specific account's interactions
    $interactionHistory = $this->interactionHistoryService->getAccountAndContactHistory($accountId);
    include __DIR__ . '/../views/interaction_history_detail.view.php'; // View for detailed interaction
}

//////////////


//////ENHANCED HISTORY 2///////////////////////
public function showAllInteractionHistorySorted() {
    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'contacted_at';
    $direction = isset($_GET['direction']) ? $_GET['direction'] : 'DESC';

    // Fetch sorted interactions
    $accountInteractions = $this->interactionHistoryService->getAllInteractions($orderBy, $direction);

    // Pass the data to the view
    include __DIR__ . '/../views/test.php';
}

    
///HISTORY above

    // Log contact interaction
    public function logContactInteraction() {
        $contactId = $_POST['contact_id'];
        $userId = $_POST['user_id'];
        $targetListId = $_POST['target_list_id'] ?? null;
        $nextContactDate = !empty($_POST['next_contact_date']) ? $_POST['next_contact_date'] : null;
        $notes = $_POST['notes'] ?? '';
        $outcome = $_POST['outcome'];
        $contactMethod = $_POST['contact_method'] ?? null;

        $this->interactionHistoryService->logContactInteraction(
            $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod
        );

        // Redirect or return a response
        echo json_encode(['success' => true]);
    }

    // Log account interaction
    public function logAccountInteraction() {
        $accountId = $_POST['account_id'];
        $userId = $_POST['user_id'];
        $targetListId = $_POST['target_list_id'] ?? null;
        $outcome = $_POST['outcome'] ?? 'check contact';
        $notes = !empty($_POST['notes']) ? $_POST['notes'] : null;
        $contactMethod = $_POST['contact_method'] ?? null;
        $nextContactDate = !empty($_POST['next_contact_date']) ? $_POST['next_contact_date'] : null;

        $this->interactionHistoryService->logAccountInteraction(
            $accountId, $userId, $targetListId, $outcome, $notes, $contactMethod, $nextContactDate
        );
        // Redirect or return a response
        echo json_encode(['success' => true]);
    }
    




//  // Fetch interaction history for a specific account
//  public function getAccountHistory($accountId) {
//     $accountHistory = $this->interactionHistoryService->getAccountHistory($accountId);
    
//     if ($accountHistory) {
//         echo json_encode($accountHistory);
//     } else {
//         echo json_encode(['message' => 'No interaction history found for this account']);
//     }
// }


//     // Fetch interaction history for a specific contact
// public function getContactHistory($contactId) {
//     $contactHistory = $this->interactionHistoryService->getContactHistory($contactId);
    
//     if ($contactHistory) {
//         echo json_encode($contactHistory);
//     } else {
//         echo json_encode(['message' => 'No interaction history found for this contact']);
//     }
// }
    
}
