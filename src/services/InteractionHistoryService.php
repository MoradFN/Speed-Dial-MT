<?php

class InteractionHistoryService {
    private $historyAccountInteractionModel;
    private $historyContactInteractionModel;
    private $accountContactRelationModel;

    public function __construct($historyAccountInteractionModel, $historyContactInteractionModel, $accountContactRelationModel) {
        $this->historyAccountInteractionModel = $historyAccountInteractionModel;
        $this->historyContactInteractionModel = $historyContactInteractionModel;
        $this->accountContactRelationModel = $accountContactRelationModel; // This is the missing dependency
    }

  ////HISTORY


  ////////////////////////WORK IN PROGRESS(WORKING)////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

 // Fetch all account interactions along with related contact interactions från hsitory account & histry contact model.
//          /// TEMPORARILY COMMENTED OUT. <-----------------------------------------------------
 public function getAllAccountInteractionsWithContacts() {
    // Step 1: Fetch all account interactions with account name from the model
    $accountInteractions = $this->historyAccountInteractionModel->getAllAccountInteractions();

    // Step 2: Loop through each account interaction and fetch related contact interaction
    foreach ($accountInteractions as &$accountInteraction) {
        if (!empty($accountInteraction['related_contact_interaction_id'])) {
            $relatedContactInteraction = $this->historyContactInteractionModel->getContactInteractionById($accountInteraction['related_contact_interaction_id']);
            $accountInteraction['related_contact_interaction'] = $relatedContactInteraction;
        } else {
            $accountInteraction['related_contact_interaction'] = null;
        }
    }

    return $accountInteractions;
}
////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////// ENHANCED HISTORY

public function getAllInteractions($orderBy = 'contacted_at', $direction = 'DESC') {
    // Step 1: Fetch all account interactions with details using the account interaction model
    $historyInteractions = $this->historyAccountInteractionModel->getAllAccountInteractionsWithDetails($orderBy, $direction);

    // Collect all related contact interaction IDs
    $interactionIds = array_filter(array_column($historyInteractions, 'related_contact_interaction_id'));

    // Ensure that $interactionIds is not empty before calling the model
    $relatedContactInteractions = [];
    if (!empty($interactionIds)) {
        // Step 2: Fetch related contact interactions in bulk using the new model method
        $relatedContactInteractions = $this->historyContactInteractionModel->getRelatedContactsByInteractionIds($interactionIds);
    }

    // Step 3: Attach related contact details to each account interaction
    foreach ($historyInteractions as &$historyInteraction) {
        $relatedId = $historyInteraction['related_contact_interaction_id'];
        $historyInteraction['related_contact_interaction'] = $relatedContactInteractions[$relatedId] ?? null;
    }

    return $historyInteractions;
}



////////////////////////////////////////////////////////////////////////////////////////////







////


// Log contact interaction
public function logContactInteraction($contactId, $userId, $targetListId, $nextContactDate, $notes, $outcome, $contactMethod) {
    // Get the account ID based on the contact ID
    $accountId = $this->accountContactRelationModel->getAccountIdByContactId($contactId);

    if ($accountId === null) {
        throw new Exception("Account not found for the given contact.");
    }

    // Insert the interaction for the contact and capture the inserted ID
    $contactInteractionId = $this->historyContactInteractionModel->insertInteraction(
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
    $this->historyAccountInteractionModel->insertInteraction(
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
    
// Fetch account interaction history along with related contact interactions KULIS?
// public function getAccountAndContactHistory($accountId = null) {
//     // Debugging the account ID in the service
//     var_dump('Account ID in Service:', $accountId);

//     // Fetch account interaction history
//     if($accountId) {
//         $accountHistory = $this->historyAccountInteractionModel->getInteractionsByAccountId($accountId);
//     }else{
//         $accountHistory = $this->historyAccountInteractionModel->getInteractionsByAccount();
//     }
   
//     // Debug the result after fetching from the model
//     var_dump('$accountHistory:', $accountHistory);

//     // Initialize result array
//     $result = [
//         'account_history' => []
//     ];

//     // Loop through each account interaction and fetch the related contact interaction history
//     foreach ($accountHistory as $accountInteraction) {
//         $relatedContactInteractionId = $accountInteraction['related_contact_interaction_id'];

//         // Fetch related contact interaction if the ID exists
//         if ($relatedContactInteractionId) {
//             $relatedContactInteraction = $this->historyContactInteractionModel->getInteractionById($relatedContactInteractionId);
//             $accountInteraction['related_contact_interaction'] = $relatedContactInteraction;
//         } else {
//             $accountInteraction['related_contact_interaction'] = null;
//         }

//         // Add to the result
//         $result['account_history'][] = $accountInteraction;
//     }

//     return $result;
// }


    // // Fetch account interaction history along with related contacts' interaction history
    // public function getAccountAndContactHistory($accountId) {
    //     // Fetch account interaction history
    //     $accountHistory = $this->historyAccountInteractionModel->getInteractionHistoryByAccountId($accountId);

    //     // Fetch all related contacts for the account
    //     $contacts = $this->historyAccountInteractionModel->getRelatedContactsByAccountId($accountId);

    //     // Initialize result array
    //     $result = [
    //         'account_history' => $accountHistory,
    //         'contacts_history' => []
    //     ];

    //     // Loop through each contact and fetch their interaction history
    //     foreach ($contacts as $contact) {
    //         $contactId = $contact['id'];
    //         $contactHistory = $this->historyContactInteractionModel->getInteractionHistoryByContactId($contactId);
    //         $result['contacts_history'][] = [
    //             'contact' => $contact,
    //             'history' => $contactHistory
    //         ];
    //     }

    //     return $result;
    // }

    // Fetch interaction history for a contact
    public function getContactHistory($contactId) {
        return $this->historyContactInteractionModel->getInteractionHistoryByContactId($contactId);
    }

    // Fetch interaction history for an account
    public function getAccountHistory($accountId) {
        return $this->historyAccountInteractionModel->getInteractionHistoryByAccountId($accountId);
    }


}
