<?php

class InteractionHistoryController {
    private $interactionHistoryService;

    public function __construct($interactionHistoryService) {
        $this->interactionHistoryService = $interactionHistoryService;
    }

    // Log contact interaction
    public function logContactInteraction() {
        $contactId = $_POST['contact_id'];
        $userId = $_POST['user_id'];
        $targetListId = $_POST['target_list_id'] ?? null;
        $nextContactDate = $_POST['next_contact_date'];
        $notes = $_POST['notes'];
        $outcome = $_POST['outcome'];
        $contactMethod = $_POST['contact_method'];

        $this->interactionHistoryService->logContactInteraction(
            $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod
        );

        // Redirect or return a response
        echo json_encode(['success' => true]);
    }

    // Log account interaction
    public function logAccountInteraction() {
        $accountId = $_POST['account_id'];
        $contactId = $_POST['contact_id'] ?? null;
        $userId = $_POST['user_id'];
        $targetListId = $_POST['target_list_id'] ?? null;
        $nextContactDate = $_POST['next_contact_date'];
        $notes = $_POST['notes'];
        $outcome = $_POST['outcome'];
        $contactMethod = $_POST['contact_method'];

        $this->interactionHistoryService->logAccountInteraction(
            $accountId, $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod
        );

        // Redirect or return a response
        echo json_encode(['success' => true]);
    }

    // Fetch account interaction history along with related contacts' interaction history
    public function getAccountAndContactHistory($accountId) {
        $history = $this->interactionHistoryService->getAccountAndContactHistory($accountId);

        // Return the combined history as JSON
        echo json_encode($history);
    }


 // Fetch interaction history for a specific account
 public function getAccountHistory($accountId) {
    $accountHistory = $this->interactionHistoryService->getAccountHistory($accountId);
    
    if ($accountHistory) {
        echo json_encode($accountHistory);
    } else {
        echo json_encode(['message' => 'No interaction history found for this account']);
    }
}


    // Fetch interaction history for a specific contact
public function getContactHistory($contactId) {
    $contactHistory = $this->interactionHistoryService->getContactHistory($contactId);
    
    if ($contactHistory) {
        echo json_encode($contactHistory);
    } else {
        echo json_encode(['message' => 'No interaction history found for this contact']);
    }
}
    
}
