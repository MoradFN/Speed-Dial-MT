<?php
// index.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/models/AccountModel.php';
require_once __DIR__ . '/../src/models/ContactModel.php';
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/models/TargetListAccountRelationModel.php';
require_once __DIR__ . '/../src/controllers/TargetListController.php';
require_once __DIR__ . '/../src/models/AccountInteractionModel.php';
require_once __DIR__ . '/../src/models/ContactInteractionModel.php';
require_once __DIR__ . '/../src/services/InteractionService.php';
require_once __DIR__ . '/../src/controllers/InteractionController.php';

// Instantiate the model and service
$targetListModel = new TargetListModel($db);
$accountModel = new AccountModel($db);
$contactModel = new ContactModel($db);
$targetListAccountRelationModel = new TargetListAccountRelationModel($db);
$accountInteractionModel = new AccountInteractionModel($db);
$contactInteractionModel = new ContactInteractionModel($db);
// Instantiate the unified interaction service -- De är i samma service?
$interactionService = new InteractionService($accountInteractionModel, $contactInteractionModel);
$targetListService = new TargetListService($targetListModel, $accountModel, $contactModel, $targetListAccountRelationModel);  // Pass the model to the service


// Routing logic
$route = isset($_GET['route']) ? $_GET['route'] : 'home'; // Default route is home
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    switch ($route) {
        case 'home':
            include __DIR__ . '/../src/views/home.php';  // Relative path from index.php to the views folder
            break;

        case 'target-lists':
            // Initialize the controller and call the method to list all target lists
            $controller = new TargetListController($targetListService);  // Pass the service to the controller
            $controller->listAllTargetLists();
            break;

        case 'target-list-detail':
            // Get the target list ID from the URL and show the details of that list
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $controller = new TargetListController($targetListService);  // Pass the service to the controller
            $controller->showTargetList($id);
            break;

        case 'test':
            include __DIR__ . '/../html/test.php';  // Load the test page
            break;            

        default:
            include __DIR__ . '/../src/views/404.php';  // Load 404 page if no matching route is found
            break;

    }
    
} elseif ($method === 'POST') {
    // Handle POST requests (e.g., form submissions)
    switch ($route) {
        case 'add-target-list':
            // Initialize the controller and handle form submission
            $controller = new TargetListController($targetListService);  // Pass the service to the controller
            $controller->createTargetList($_POST);
            break;
            
        case 'log-interaction':
                    // Initialize the InteractionController
                    $interactionController = new InteractionController($interactionService);
                    $interactionController->logInteraction($_POST);  // Handle logging interactions
                    break;

        default:
            include __DIR__ . '/../src/views/404.php';  // Load 404 page if no matching route is found
            break;
    }
}

