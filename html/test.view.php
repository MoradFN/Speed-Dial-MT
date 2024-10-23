<?php
// html/index.php
// Include config.php to establish the database connection
require_once __DIR__ . '/../config/config.php';

//MODEL TESTING
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/models/AccountModel.php';

//SERVICE TESTING
require_once __DIR__ . '/../src/services/TargetListService.php';

// CONTROLLER TESTING
require_once __DIR__ . '/../src/controllers/TargetListController.php';



// Create an instance of AccountModel and pass the $db connection
$accountModel = new AccountModel($db);
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);
$targetListController = new TargetListController($targetListService);


if (isset($_GET['id'])) {
    // Show the detail view for a specific target list
    $targetListController->showTargetList($_GET['id']);
} else {
    // Show all target lists
    $targetListController->listAllTargetLists();
}


