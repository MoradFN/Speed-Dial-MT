<?php
// Include the necessary files
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/controllers/TargetListController.php';

// Instantiate the TargetListModel and TargetListService
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);
$controller = new TargetListController($targetListService);

// Fetch and display the target list details
if (isset($_GET['id'])) {
    $controller->showTargetList($_GET['id']);
} else {
    echo "No target list ID provided.";
}
