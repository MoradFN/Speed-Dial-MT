<?php

class InteractionHistoryService {
    private $accountInteractionModel;
    private $contactInteractionModel;
    private $accountContactRelationModel;

    public function __construct($accountInteractionModel, $contactInteractionModel, $accountContactRelationModel) {
        $this->accountInteractionModel = $accountInteractionModel;
        $this->contactInteractionModel = $contactInteractionModel;
        $this->accountContactRelationModel = $accountContactRelationModel; // This is the missing dependency
    }

    // Log contact interaction
    public function logContactInteraction($contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
        // Get the account ID based on the contact ID
        $accountId = $this->accountContactRelationModel->getAccountIdByContactId($contactId);

        if ($accountId === null) {
            throw new Exception("Account not found for the given contact.");
        }

        // Insert the interaction for the contact
        $this->contactInteractionModel->insertInteraction(
            $contactId,
            $userId,
            $targetListId,
            $nextContactDate,
            $notes,
            $outcome,
            $contactMethod
        );

        // Insert the interaction for the account
        $this->accountInteractionModel->insertInteraction(
            $accountId,
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
