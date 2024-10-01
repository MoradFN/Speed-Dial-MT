<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/controllers/TargetListController.php';

// Instantiate the TargetListModel and TargetListService
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);

// Initialize the controller with the service
$controller = new TargetListController($targetListService);

if (isset($_GET['id'])) {
    $controller->showTargetList($_GET['id']);
} else {
    $controller->listAllTargetLists();
}
