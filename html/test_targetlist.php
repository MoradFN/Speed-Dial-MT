<?php
// html/test_targetlist.php
require_once __DIR__ . '/../config/config.php'; // Load database configuration
require_once __DIR__ . '/../src/models/TargetListModel.php'; // Load TargetListModel
require_once __DIR__ . '/../src/services/TargetListService.php'; // Load TargetListService

// Instantiate the TargetList model, passing the $db connection from config.php
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);
// Test fetching all target lists

////////////////////////// MODEL TEST //////////////////////////////////
///////////////////////////////////////////////////////////////////////
// $targetLists = $targetListModel->getAllTargetLists();
// echo "<pre>";
// // print_r($targetLists); // This will print the list of target lists in a readable format
// echo "</pre>";

// //test getting relations from target list relations table with account and related contacts.(With data structure.)
// // Hämtar alla accounts och contacts och binder ihop kontakt till varje account med hjälp av target_relation_table
// $targetListId = 1; // Assume this ID exists
// $accountsAndContacts = $targetListModel->getAccountsAndContactsByTargetList($targetListId);
// echo "<pre>";
// print_r($accountsAndContacts); // This will print the accounts and contacts in the list
// echo "</pre>";
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

////////////////////////// SERVICE TEST ////////////////////////////////
///////////////////////////////////////////////////////////////////////

try {
    // Fetch all target lists
    $targetLists = $targetListService->getAllTargetLists();
    // Output the results in a readable format
    echo "<h1>Target Lists</h1>";
    echo "<pre>";
    print_r($targetLists);
    echo "</pre>";
} catch (Exception $e) {
    // Handle exceptions (e.g., target list not found)
    echo "Error: " . $e->getMessage();
}

try {
    // Fetch a specific target list (e.g., target list with ID 1)
    $targetListId = 1; // Use an existing target list ID for testing
    $targetListWithAccountsAndContacts = $targetListService->getTargetListWithAccountsAndContacts($targetListId);

    // Output the results in a readable format
    echo "<h1>Target List Details</h1>";
    echo "<pre>";
    print_r($targetListWithAccountsAndContacts);
    echo "</pre>";

} catch (Exception $e) {
    // Handle exceptions (e.g., target list not found)
    echo "Error: " . $e->getMessage();
}
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////