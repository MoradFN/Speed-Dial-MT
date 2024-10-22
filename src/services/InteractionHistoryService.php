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

    // Insert the interaction for the contact and capture the inserted ID
    $contactInteractionId = $this->contactInteractionModel->insertInteraction(
        $contactId,
        $userId,
        $targetListId,
        $outcome,
        $notes,
        $contactMethod,
        $nextContactDate
    );

    // Check if contact interaction was inserted successfully
    if ($contactInteractionId === false) {
        throw new Exception("Failed to insert contact interaction.");
    }

    // Insert the interaction for the account, passing the related contact interaction ID
    $this->accountInteractionModel->insertInteraction(
        $accountId,
        $userId,
        $targetListId,
        $contactInteractionId, 
        $outcome,
        $notes,
        $contactMethod,    
        $nextContactDate
    );
}
    
    // Fetch account interaction history along with related contacts' interaction history[MODIFIED] -HANTERAR N+1
    public function getAccountAndContactHistory($accountId) {
        // Fetch account interaction history
        $accountHistory = $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);
    
        // Fetch all related contacts for the account
        $contacts = $this->accountInteractionModel->getRelatedContactsByAccountId($accountId);
    
        // Fetch all contact interactions in one query (optimized)
        $contactIds = array_column($contacts, 'id');  // Extract all contact IDs
        $contactsHistory = $this->contactInteractionModel->getInteractionHistoryByContactIds($contactIds);
    
        // Group contact histories with the respective contacts
        $result = [
            'account_history' => $accountHistory,
            'contacts_history' => []
        ];
    
        // Organize contact history by contact ID
        foreach ($contacts as $contact) {
            $contactId = $contact['id'];
            $contactHistory = array_filter($contactsHistory, function($history) use ($contactId) {
                return $history['contact_id'] == $contactId;
            });
            $result['contacts_history'][] = [
                'contact' => $contact,
                'history' => $contactHistory
            ];
        }
    
        return $result;
    }


    // // Fetch account interaction history along with related contacts' interaction history
    // public function getAccountAndContactHistory($accountId) {
    //     // Fetch account interaction history
    //     $accountHistory = $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);

    //     // Fetch all related contacts for the account
    //     $contacts = $this->accountInteractionModel->getRelatedContactsByAccountId($accountId);

    //     // Initialize result array
    //     $result = [
    //         'account_history' => $accountHistory,
    //         'contacts_history' => []
    //     ];

    //     // Loop through each contact and fetch their interaction history
    //     foreach ($contacts as $contact) {
    //         $contactId = $contact['id'];
    //         $contactHistory = $this->contactInteractionModel->getInteractionHistoryByContactId($contactId);
    //         $result['contacts_history'][] = [
    //             'contact' => $contact,
    //             'history' => $contactHistory
    //         ];
    //     }

    //     return $result;
    // }

    // Fetch interaction history for a contact
    public function getContactHistory($contactId) {
        return $this->contactInteractionModel->getInteractionHistoryByContactId($contactId);
    }

    // Fetch interaction history for an account
    public function getAccountHistory($accountId) {
        return $this->accountInteractionModel->getInteractionHistoryByAccountId($accountId);
    }


}
