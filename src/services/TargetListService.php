<?php

class TargetListService {
    private $targetListModel;
    private $accountModel;
    private $contactModel;
    private $targetListAccountRelationModel;
    private $accountContactRelationModel;

      public function __construct($targetListModel, $accountModel, $contactModel, $targetListAccountRelationModel, $accountContactRelationModel) {
        $this->targetListModel = $targetListModel;
        $this->accountModel = $accountModel;
        $this->contactModel = $contactModel;
        $this->targetListAccountRelationModel = $targetListAccountRelationModel;
        $this->accountContactRelationModel = $accountContactRelationModel;
    }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////     DONE     //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Inject the models via the constructor
  
    // Fetch all target lists with filtering logic if needed
    public function getAllTargetLists($filters = []) {
        // Business logic can be added here (e.g., filtering or sorting)
        return $this->targetListModel->getAllTargetLists($filters);
    }

  
    // Fetch a specific target list and its associated accounts and contacts from TargetListModel
   public function getTargetListWithAccountsAndContacts($targetListId) {
    // Fetch the target list data
    $targetList = $this->targetListModel->getTargetListById($targetListId);
    if (!$targetList) {
        throw new Exception("Target list not found.");
    }

    // Step 1: Fetch account IDs from target_list_account_relation using the TargetListAccountRelationModel
    $accountIdsResult = $this->targetListAccountRelationModel->getAccountIdsByTargetListId($targetListId);
    $accountIds = array_column($accountIdsResult, 'account_id'); // Extract the IDs into a flat array

    if (empty($accountIds)) {
        $targetList['accounts'] = [];  // No accounts found, return an empty list
        return $targetList;
    }

    // Step 2: Fetch the account details from AccountModel using the fetched account IDs
    $accounts = $this->accountModel->getAccountsByIds($accountIds);

    // Step 3: Fetch contacts using AccountContactRelationModel and group them under each account
    foreach ($accounts as &$account) {
        // Fetch contact_ids from account_contact_relation table
        $contactIdsResult = $this->accountContactRelationModel->getContactIdsByAccountId($account['account_id']);
        $contactIds = array_column($contactIdsResult, 'contact_id'); 

        // Fetch contacts from ContactModel using the fetched contact IDs
        $account['contacts'] = $this->contactModel->getContactsByIds($contactIds);  // Assuming you have this method
    }

    // Assign accounts and contacts to the target list
    $targetList['accounts'] = $accounts;
    
    return $targetList;
}

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    

    // // Adding interaction notes for a specific account/contact
    // public function addInteractionNotes($accountId, $userId, $notes, $contactedAt, $nextContactDate = null) {
    //     // Business logic for validating or modifying notes before storing
    //     return $this->targetListModel->addAccountInteractionHistory($accountId, $userId, $notes, $contactedAt, $nextContactDate);
    // }

    // // Lock a target list for exclusive user access
    // public function lockTargetList($targetListId, $userId) {
    //     // Additional business logic, if any, can be added here
    //     return $this->targetListModel->lockTargetList($targetListId, $userId);
    // }

    // // Unlock a target list
    // public function unlockTargetList($targetListId) {
    //     return $this->targetListModel->unlockTargetList($targetListId);
    // }

    // // Update the status of a target list (e.g., active, completed, archived)
    // public function updateTargetListStatus($targetListId, $status) {
    //     // Apply validation or business rules around status updates
    //     return $this->targetListModel->updateTargetListStatus($targetListId, $status);
    // }
}
