<?php
// html/index.php
// Include config.php to establish the database connection
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/models/AccountModel.php';

// Create an instance of AccountModel and pass the $db connection
$accountModel = new AccountModel($db);


// Fetch all accounts using the model
$accounts = $accountModel->getAllAccounts();

// Display the accounts
echo '<h1>Account List</h1>';
foreach ($accounts as $account) {
    echo '<p>' . $account['name'] . '</p>';
}
