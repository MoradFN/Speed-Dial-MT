<?php
// public/targetlist.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/controllers/TargetListController.php';

// Create database connection
$pdo = Database::connect();
$db = new Database($pdo);

// Instantiate models, services, and controllers
$targetListModel = new TargetListModel($db);
$targetListService = new TargetListService($targetListModel);
$targetListController = new TargetListController($targetListService);

// Display the target list
$targetListController->showTargetList(1);  // Example target list ID
