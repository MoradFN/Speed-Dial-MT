<?php
// src/services/AccountService.php

class AccountService {
    private $accountModel;
    private $contactModel;  // Add this if you need to interact with contacts as well

    public function __construct($accountModel, $contactModel = null) {
        $this->accountModel = $accountModel;
        $this->contactModel = $contactModel;
    }

    // Fetch and process all accounts
    public function getAllAccounts() {
        $accounts = $this->accountModel->getAllAccounts();
        
        // Example business logic: Append 'active' status to each account
        foreach ($accounts as &$account) {
            $account['status'] = 'active';  // Business logic example
        }

        return $accounts;
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

    // Fetch account with its related contacts
    public function getAccountWithContacts($accountId) {
        $account = $this->getAccountById($accountId);

        if ($this->contactModel) {
            // Fetch related contacts if the contact model is injected
            $account['contacts'] = $this->contactModel->getContactsByAccountId($accountId);
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
