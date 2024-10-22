<?php

class InteractionHistoryController {
    private $interactionHistoryService;

    public function __construct($interactionHistoryService) {
        $this->interactionHistoryService = $interactionHistoryService;
    }


    ///HISTORY
    // Method to display the account and contact interaction history
    public function getAccountAndContactHistory($accountId) {
        // Fetch the interaction history for the account
        $interactionHistory = $this->interactionHistoryService->getAccountAndContactHistory($accountId);
        var_dump('Account ID in Controller:', $accountId); // Add this line for debugging
    
        // Pass data to the view
        include __DIR__ . '/../views/interaction_history.view.php';
    }
    
///

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
