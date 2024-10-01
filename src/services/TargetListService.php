<?php

class TargetListService {
    private $targetListModel;
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////     DONE     //////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($targetListModel) {
        $this->targetListModel = $targetListModel;
    }

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
    
        // Fetch the already grouped accounts and contacts from the model
        $targetList['accounts'] = $this->targetListModel->getAccountsAndContactsByTargetList($targetListId);
    
        return $targetList;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    

    // Adding interaction notes for a specific account/contact
    public function addInteractionNotes($accountId, $userId, $notes, $contactedAt, $nextContactDate = null) {
        // Business logic for validating or modifying notes before storing
        return $this->targetListModel->addAccountInteractionHistory($accountId, $userId, $notes, $contactedAt, $nextContactDate);
    }

    // Lock a target list for exclusive user access
    public function lockTargetList($targetListId, $userId) {
        // Additional business logic, if any, can be added here
        return $this->targetListModel->lockTargetList($targetListId, $userId);
    }

    // Unlock a target list
    public function unlockTargetList($targetListId) {
        return $this->targetListModel->unlockTargetList($targetListId);
    }

    // Update the status of a target list (e.g., active, completed, archived)
    public function updateTargetListStatus($targetListId, $status) {
        // Apply validation or business rules around status updates
        return $this->targetListModel->updateTargetListStatus($targetListId, $status);
    }
}
