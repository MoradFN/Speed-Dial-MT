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

// Instantiate models
$historyAccountInteractionModel = new HistoryAccountInteractionModel($db);
$historyContactInteractionModel = new HistoryContactInteractionModel($db);
$accountContactRelationModel = new AccountContactRelationModel($db);

// Instantiate the service
$interactionHistoryService = new InteractionHistoryService(
    $historyAccountInteractionModel, 
    $historyContactInteractionModel, 
    $accountContactRelationModel
);


// List of sorting fields to test
$fieldsToTest = [
    'account_name', 'contact_name', 'target_list_name', 'campaign_name',
    'hai.contacted_at', 'hai.next_contact_date', 'hai.updated_at', 'hai.outcome'
];


// Loop through each field and test sorting by 'ASC' and 'DESC'
foreach ($fieldsToTest as $field) {
    foreach (['ASC', 'DESC'] as $direction) {
        echo "<h2>Testing Sorting by: $field $direction</h2>";

        // Fetch sorted interactions using the service
        $accountInteractionsSorted = $interactionHistoryService->getAllInteractions($field, $direction);

        // Output the result in a readable format
        echo "<pre>";
        print_r($accountInteractionsSorted);
        echo "</pre>";
    }
}