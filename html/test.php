<?php
// Include the header navigation
include __DIR__ . '/../src/views/header.php';
// html/test.php
require_once __DIR__ . '/../config/config.php'; // Load database configuration

//MODELS TESTING
require_once __DIR__ . '/../src/models/HistoryAccountInteractionModel.php'; // Load HistoryAccountInteractionModel
require_once __DIR__ . '/../src/models/HistoryContactInteractionModel.php'; // Load HistoryContactInteractionModel


//SERVICES TESTING

require_once __DIR__ . '/../src/services/InteractionHistoryService.php';

// TARGETLIST TESTING
// $targetListModel = new TargetListModel($db);
// $targetListService = new TargetListService($targetListModel);

// Instantiate models
$historyAccountInteractionModel = new HistoryAccountInteractionModel($db);
$historyContactInteractionModel = new HistoryContactInteractionModel($db);

// Instantiate the service
$interactionHistoryService = new InteractionHistoryService(
    $historyAccountInteractionModel, 
    $historyContactInteractionModel, 
    $accountContactRelationModel // Make sure this is instantiated and passed
);


// Fetch all interactions
// Simulating fetching all account interactions with related contact interactions
// $historyAccountInteractionModel->setContactInteractionModel($historyContactInteractionModel);
// $accountInteractions = $historyAccountInteractionModel->getAllAccountInteractionsWithContacts();


// Fetch and display the account and contact interactions
$accountInteractionsWithContacts = $interactionHistoryService->getAllAccountInteractionsWithContacts();





////////////////////////// TEST //////////////////////////////////
///////////////////////////////////////////////////////////////////////

// Output the result
echo "<pre>";
print_r($accountInteractionsWithContacts);
echo "</pre>";

?>

<?php
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

////////////////////////// SERVICE TEST ////////////////////////////////
///////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////