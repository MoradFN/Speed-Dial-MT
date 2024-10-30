<?php
// Include the header navigation
include __DIR__ . '/../src/views/header.php';
// html/test.php
require_once __DIR__ . '/../config/config.php'; // Load database configuration

//MODELS TESTING
require_once __DIR__ . '/../src/models/HistoryAccountInteractionModel.php'; // Load HistoryAccountInteractionModel
require_once __DIR__ . '/../src/models/HistoryContactInteractionModel.php'; // Load HistoryContactInteractionModel
require_once __DIR__ . '/../src/models/AccountContactRelationModel.php'; 

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


        // Fetch sorted interactions using the service
$allDetailedInteractions = $historyAccountInteractionModel->getDetailedInteractionHistory([], 'contacted_at', 'DESC');

echo "<pre>";
print_r($allDetailedInteractions);
echo "</pre>";
