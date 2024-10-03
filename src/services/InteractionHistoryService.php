<?php

class InteractionHistoryService {
    private $contactInteractionModel;
    private $accountInteractionModel;

    public function __construct($contactInteractionModel, $accountInteractionModel) {
        $this->contactInteractionModel = $contactInteractionModel;
        $this->accountInteractionModel = $accountInteractionModel;
    }

    // Log contact interaction
    public function logContactInteraction($contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
        $this->contactInteractionModel->insertInteraction(
            $contactId,
            $userId,
            $targetListId,
            $nextContactDate,
            $notes,
            $outcome,
            $contactMethod
        );
        // Update next contact date in the account interaction history
        $this->accountInteractionModel->updateNextContactDateByContact($contactId, $nextContactDate);
    }

    // Log account interaction
    public function logAccountInteraction($accountId, $contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
        $this->accountInteractionModel->insertInteraction(
            $accountId,
            $contactId,
            $userId,
            $targetListId,
            $nextContactDate,
            $notes,
            $outcome,
            $contactMethod
        );
    }

    // Fetch interaction history for a contact
    public function getContactHistory($contactId) {
        return $this->contactInteractionModel->getInteractionHistoryByContactId($contactId);
    }

    // Fetch interaction history for an account
    public function getAccountHistory($accountId) {
        return $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);
    }

    // Fetch account interaction history along with related contacts' interaction history
    public function getAccountAndContactHistory($accountId) {
        // Fetch account interaction history
        $accountHistory = $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);

        // Fetch all related contacts for the account
        $contacts = $this->accountInteractionModel->getRelatedContactsByAccountId($accountId);

        // Initialize result array
        $result = [
            'account_history' => $accountHistory,
            'contacts_history' => []
        ];

        // Loop through each contact and fetch their interaction history
        foreach ($contacts as $contact) {
            $contactId = $contact['id'];
            $contactHistory = $this->contactInteractionModel->getInteractionHistoryByContactId($contactId);
            $result['contacts_history'][] = [
                'contact' => $contact,
                'history' => $contactHistory
            ];
        }

        return $result;
    }
}
