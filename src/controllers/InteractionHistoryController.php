<?php
require_once __DIR__ . '/../helpers/Helpers.php';

class InteractionHistoryController {
    private $interactionHistoryService;

    public function __construct($interactionHistoryService) {
        $this->interactionHistoryService = $interactionHistoryService;
    }


    ///HISTORY
    // Method to show interaction history with filters, sorting, and pagination // STANDARD -LANDING
    public function showInteractionHistory()
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
$orderBy = $_GET['orderBy'] ?? 'contact_contacted_at';
$direction = $_GET['direction'] ?? 'DESC';
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10); //MTTODO / CHECK STANDARD PAGINATION

    // Retrieve data from the service
    $response = $this->interactionHistoryService->getInteractionHistory($filters, $orderBy, $direction, $page, $limit);
    $viewData = [
        'interactionHistory' => $response['data'] ?? [],         // Empty array if no data
        'totalPages' => $response['total_pages'] ?? 1,           // Default to 1 page
        'totalRecords' => $response['total_records'] ?? 0,       // Default to 0 records
        'page' => $page,
        'limit' => $limit,
        'filters' => $filters,
        'orderBy' => $orderBy,
        'direction' => $direction,
    ];
    

    // Check if it's an API request
    if (RequestHelper::isApiRequest()) {
        ResponseHelper::jsonResponse($viewData);
    } else {
        include __DIR__ . '/../views/interaction_history.view.php';
    }
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

    // Log contact interaction + account interaction if provided? see service.
    public function logContactInteraction() {
      // HELPER FÖR: Set content-type to JSON for API response och Read JSON input
    $input = RequestHelper::getInput();

    $requiredFields = ['contact_id', 'user_id', 'target_list_id', 'outcome'];
    if (!ValidationHelper::validateRequiredFields($requiredFields, $input)) {
        ResponseHelper::jsonResponse(['success' => false, 'message' => 'Missing required fields.'], 400);
        return;
    }


        // Retrieve variables from JSON input
        $contactId = $input['contact_id'];
        $userId = $input['user_id'];
        $targetListId = $input['target_list_id'] ?? null;
        $nextContactDate = !empty($input['next_contact_date']) ? $input['next_contact_date'] : null;
        $notes = $input['notes'] ?? '';
        $outcome = $input['outcome'];
        $contactMethod = $input['contact_method'] ?? null;



        $this->interactionHistoryService->logContactInteraction(
            $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod
        );

        ResponseHelper::jsonResponse(['success' => true, 'message' => 'Contact interaction record created successfully.']);
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
