<?php

// src/services/TargetsListService.php

class TargetsListService {
    private $targetListModel;
    private $accountModel;

    public function __construct($targetListModel, $accountModel) {
        $this->targetListModel = $targetListModel;
        $this->accountModel = $accountModel;
    }

    // Get all target lists, possibly with filters
    public function getAllTargetLists($filters = []) {
        // Example business logic: filter only active or in-progress target lists
        if (!isset($filters['status'])) {
            $filters['status'] = ['active', 'in_progress'];
        }
        
        // Call the model to fetch the target lists with filters
        $targetLists = $this->targetListModel->getAllTargetLists($filters);
        
        // Additional processing can be done here if needed
        return $targetLists;
    }

    // Get target list by ID, with accounts and contacts
    public function getTargetListWithAccountsAndContacts($targetListId) {
        // Fetch the target list
        $targetList = $this->targetListModel->getTargetListById($targetListId);
        if (!$targetList) {
            throw new Exception("Target list not found.");
        }

        // Fetch associated accounts and contacts
        $accountsAndContacts = $this->targetListModel->getAccountsAndContactsByTargetList($targetListId);
        
        // Attach the accounts and contacts to the target list
        $targetList['accounts'] = $accountsAndContacts;

        return $targetList;
    }

    // Assign a user to a target list
    public function assignUserToTargetList($targetListId, $userId) {
        // Validate that the target list exists
        $targetList = $this->targetListModel->getTargetListById($targetListId);
        if (!$targetList) {
            throw new Exception("Target list not found.");
        }

        // Call the model to assign the user
        return $this->targetListModel->assignUserToTargetList($targetListId, $userId);
    }

    // Lock the target list for exclusive access
    public function lockTargetList($targetListId, $userId) {
        // Check if the list is already locked
        $targetList = $this->targetListModel->getTargetListById($targetListId);
        if ($targetList['locked_by']) {
            throw new Exception("This target list is already locked by another user.");
        }

        // Lock the list for the current user
        return $this->targetListModel->lockTargetList($targetListId, $userId);
    }

    // Unlock the target list
    public function unlockTargetList($targetListId, $userId) {
        // Check if the user trying to unlock the list is the one who locked it
        $targetList = $this->targetListModel->getTargetListById($targetListId);
        if ($targetList['locked_by'] !== $userId) {
            throw new Exception("You are not authorized to unlock this target list.");
        }

        // Unlock the target list
        return $this->targetListModel->unlockTargetList($targetListId);
    }

    // Add interaction history for an account
    public function addAccountInteraction($accountId, $userId, $notes, $contactedAt, $nextContactDate = null) {
        // Basic validation
        if (empty($notes)) {
            throw new Exception("Interaction notes cannot be empty.");
        }

        // Add interaction history through the model
        return $this->targetListModel->addAccountInteractionHistory($accountId, $userId, $notes, $contactedAt, $nextContactDate);
    }

    // Update target list relation status (e.g., mark account as contacted)
    public function updateTargetListRelationStatus($targetListId, $accountId, $status) {
        // Fetch the target list relation first to ensure it exists
        $targetList = $this->targetListModel->getTargetListById($targetListId);
        if (!$targetList) {
            throw new Exception("Target list not found.");
        }

        // Update the status of the account within the target list
        return $this->targetListModel->updateTargetListRelationStatus($targetListId, $accountId, $status);
    }

    // Create a new target list
    public function createTargetList($name, $description = null, $campaignId = null, $assignedTo = null) {
        // Business validation (e.g., check if required fields are present)
        if (empty($name)) {
            throw new Exception("Target list name is required.");
        }

        // Call the model to create the target list
        return $this->targetListModel->createTargetList($name, $description, $campaignId, $assignedTo);
    }

    // Update the status of a target list (e.g., activate, archive)
    public function updateTargetListStatus($targetListId, $status) {
        // Basic validation
        $validStatuses = ['pending', 'active', 'completed', 'archived'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status.");
        }

        // Update the status
        return $this->targetListModel->updateTargetListStatus($targetListId, $status);
    }
}
