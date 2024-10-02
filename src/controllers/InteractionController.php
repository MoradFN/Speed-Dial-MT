<?php
class InteractionController {
    private $interactionService;

    public function __construct($interactionService) {
        $this->interactionService = $interactionService;
    }

    public function logInteraction($postData) {
        // Extract data from POST request
        $accountId = $postData['account_id'];
        $contactId = $postData['contact_id'];
        $userId = 1;  // Assuming a logged-in user
        $notes = $postData['notes'];
        $outcome = $postData['outcome'];
        $contactMethod = $postData['contact_method'];
        $duration = $postData['interaction_duration'];
        $contactedAt = $postData['contacted_at'];
        $nextContactDate = $postData['next_contact_date'] ?? null;

        // Log both account and contact interactions
        $this->interactionService->logAccountInteraction($accountId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);
        $this->interactionService->logContactInteraction($contactId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);

        // Redirect or show success message
        header('Location: ?route=target-list-detail&id=' . $accountId);
    }
}
