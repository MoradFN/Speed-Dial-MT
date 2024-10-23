<?php
// Include the header navigation
include __DIR__ . '/../src/views/header.php';
// html/test.php
require_once __DIR__ . '/../config/config.php'; // Load database configuration

//MODELS TESTING
// require_once __DIR__ . '/../src/models/TargetListModel.php'; // Load TargetListModel
require_once __DIR__ . '/../src/models/HistoryAccountInteractionModel.php'; // Load HistoryAccountInteractionModel
require_once __DIR__ . '/../src/models/HistoryContactInteractionModel.php'; // Load HistoryContactInteractionModel

//SERVICES TESTING
// require_once __DIR__ . '/../src/services/TargetListService.php'; // Load TargetListService

// TARGETLIST TESTING
// $targetListModel = new TargetListModel($db);
// $targetListService = new TargetListService($targetListModel);

//INTERACTION HISTORY TESTING
// Instantiate the models
$accountInteractionModel = new HistoryAccountInteractionModel($db);
$contactInteractionModel = new HistoryContactInteractionModel($db);
// Fetch all interactions
// Simulating fetching all account interactions with related contact interactions
$accountInteractionModel->setContactInteractionModel($historyContactInteractionModel);
$accountInteractions = $accountInteractionModel->getAllAccountInteractionsWithContacts();













////////////////////////// MODEL TEST //////////////////////////////////
///////////////////////////////////////////////////////////////////////

// Output the results
echo "<pre>";
print_r($accountInteractions);
echo "</pre>";




?>

<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

////////////////////////// SERVICE TEST ////////////////////////////////
///////////////////////////////////////////////////////////////////////

// try {
//     // Fetch all target lists
//     $targetLists = $targetListService->getAllTargetLists();
//     // Output the results in a readable format
//     echo "<h1>Target Lists</h1>";
//     echo "<pre>";
//     print_r($targetLists);
//     echo "</pre>";
// } catch (Exception $e) {
//     // Handle exceptions (e.g., target list not found)
//     echo "Error: " . $e->getMessage();
// }

// try {
//     // Fetch a specific target list (e.g., target list with ID 1)
//     $targetListId = 1; // Use an existing target list ID for testing
//     $targetListWithAccountsAndContacts = $targetListService->getTargetListWithAccountsAndContacts($targetListId);

//     // Output the results in a readable format
//     echo "<h1>Target List Details</h1>";
//     echo "<pre>";
//     print_r($targetListWithAccountsAndContacts);
//     echo "</pre>";

// } catch (Exception $e) {
//     // Handle exceptions (e.g., target list not found)
//     echo "Error: " . $e->getMessage();
// }
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////