<?php
// src/services/AccountService.php

class AccountContactService {
    private $accountModel;
    private $contactModel;  // Add this if you need to interact with contacts as well
    private $accountContactRelationModel;

    public function __construct($accountModel, $contactModel, $accountContactRelationModel = null) {
        $this->accountModel = $accountModel;
        $this->contactModel = $contactModel;
        $this->accountContactRelationModel = $accountContactRelationModel;
    }

    // Fetch account with its related contacts using the many-to-many relation
    public function getAccountWithContacts($accountId) {
        // Step 1: Fetch the account by ID
        $account = $this->accountModel->getAccountById($accountId);
        if (!$account) {
            throw new Exception("Account not found.");
        }

        // Step 2: Fetch related contact IDs from the relation model
        $contactIdsResult = $this->accountContactRelationModel->getContactIdsByAccountId($accountId);
        $contactIds = array_column($contactIdsResult, 'contact_id');  // Flatten the result

        // Step 3: Fetch contact details by these IDs
        $contacts = [];
        if (!empty($contactIds)) {
            $contacts = $this->contactModel->getContactsByIds($contactIds);
        }

        // Step 4: Append contacts to the account and return
        $account['contacts'] = $contacts;
        return $account;
    }

    // Fetch and process a single account
    public function getAccountById($id) {
        $account = $this->accountModel->getAccountById($id);
        
        // Example business logic: Check if the account is VIP
        if ($account['name'] === 'VIP Customer') {
            $account['vip_status'] = true;  // Business logic example
        }

        return $account;
    }



    // Create a new account with some validation logic
    public function createAccount($name, $email) {
        // Example: Validate input
        if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid input");
        }

        // Call the model to insert the account
        return $this->accountModel->createAccount($name, $email);
    }
}
