<?php
class InteractionService {
    private $accountInteractionModel;
    private $contactInteractionModel;

    public function __construct($accountInteractionModel, $contactInteractionModel) {
        $this->accountInteractionModel = $accountInteractionModel;
        $this->contactInteractionModel = $contactInteractionModel;
    }

    // Log interaction for an account
    public function logAccountInteraction($accountId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate = null) {
        if (empty($accountId) || empty($userId)) {
            throw new Exception('Account ID and User ID are required.');
        }

        return $this->accountInteractionModel->logInteraction($accountId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);
    }

    // Log interaction for a contact
    public function logContactInteraction($contactId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate = null) {
        if (empty($contactId) || empty($userId)) {
            throw new Exception('Contact ID and User ID are required.');
        }

        return $this->contactInteractionModel->logInteraction($contactId, $userId, $notes, $outcome, $contactMethod, $duration, $contactedAt, $nextContactDate);
    }

    // Fetch interaction history for an account
    public function getAccountInteractionHistory($accountId) {
        return $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);
    }

    // Fetch interaction history for a contact
    public function getContactInteractionHistory($contactId) {
        return $this->contactInteractionModel->getInteractionHistoryByContactId($contactId);
    }
}
