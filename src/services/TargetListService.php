<?php

class TargetListService {
    private $targetListModel;
    private $accountModel;
    private $contactModel;

      public function __construct($targetListModel, $accountModel, $contactModel) {
        $this->targetListModel = $targetListModel;
        $this->accountModel = $accountModel;
        $this->contactModel = $contactModel;
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

        // Fetch the accounts for the target list
        $accounts = $this->accountModel->getAccountsByTargetList($targetListId);

        // Fetch contacts for each account and group them under the accounts
        foreach ($accounts as &$account) {
            $account['contacts'] = $this->contactModel->getContactsByAccountId($account['account_id']);
        }

        // Assign accounts and contacts to the target list
        $targetList['accounts'] = $accounts;
        // header('Content-Type: application/json');
        // echo json_encode($targetList);
        
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
