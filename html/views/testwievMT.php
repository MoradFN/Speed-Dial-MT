<?php
// /html/index.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/models/AccountModel.php';
require_once __DIR__ . '/../services/AccountService.php';

// Create a database connection
$db = new Database();

// Instantiate the model and service
$accountModel = new AccountModel($db);
$accountService = new AccountService($accountModel);

// Fetch all accounts via the service
$accounts = $accountService->getAllAccounts();

// Output the accounts (in this example, we'll just print the array)
echo '<pre>';
print_r($accounts);
echo '</pre>';
