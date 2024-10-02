<?php
// html/targetlists.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/controllers/TargetListController.php';

// Instantiate the TargetListModel and TargetListService
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);
// Initialize the controller with the service
$controller = new TargetListController($targetListService);

// Check if an ID is passed (show details) or not (list all)
if (isset($_GET['id'])) {
    // Show the details of the specific target list
    $controller->showTargetList($_GET['id']);
} else {
    // List all target lists
    $controller->listAllTargetLists();
}
