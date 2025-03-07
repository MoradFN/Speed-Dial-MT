<?php
// index.php
require_once __DIR__ . '/../config/config.php';
//MODELS
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../src/models/AccountModel.php';
require_once __DIR__ . '/../src/models/ContactModel.php';
require_once __DIR__ . '/../src/models/AccountContactRelationModel.php';
require_once __DIR__ . '/../src/models/TargetListAccountRelationModel.php';
require_once __DIR__ . '/../src/models/HistoryAccountInteractionModel.php';
require_once __DIR__ . '/../src/models/HistoryContactInteractionModel.php';
//SERVICES
require_once __DIR__ . '/../src/services/TargetListService.php';
require_once __DIR__ . '/../src/services/InteractionHistoryService.php';
//CONTROLLERS
require_once __DIR__ . '/../src/controllers/TargetListController.php';
require_once __DIR__ . '/../src/controllers/InteractionHistoryController.php';

// Instantiate the model and service
$targetListModel = new TargetListModel($db);
$accountModel = new AccountModel($db);
$contactModel = new ContactModel($db);
$targetListAccountRelationModel = new TargetListAccountRelationModel($db);
$historyAccountInteractionModel = new HistoryAccountInteractionModel($db);
$historyContactInteractionModel = new HistoryContactInteractionModel($db);
$accountContactRelationModel = new AccountContactRelationModel($db);

// Instantiate the unified interaction service -- De är i samma service?
$interactionHistoryService = new InteractionHistoryService($historyAccountInteractionModel, $historyContactInteractionModel,$accountContactRelationModel, $accountModel);
$targetListService = new TargetListService($targetListModel, $accountModel, $contactModel, $targetListAccountRelationModel, $accountContactRelationModel);


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

        case 'interaction-history':
            // Initialize the controller and call the method
            $controller = new InteractionHistoryController($interactionHistoryService);
            $controller->showInteractionHistory();
            break;

            
        case 'interaction-history-account':
            if (isset($_GET['account_id']) && $_GET['account_id'] !== '') {
                $controller = new InteractionHistoryController($interactionHistoryService);
                $controller->showInteractionHistoryDetail($_GET['account_id']);
                } else {
                    $controller = new InteractionHistoryController($interactionHistoryService);
                    $controller->showAllInteractionHistory();
                }
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
            
        case 'log-contact-interaction':
            $interactionController = new InteractionHistoryController($interactionHistoryService);
            $interactionController->logContactInteraction();
            break;

        case 'log-account-interaction':
            $interactionController = new InteractionHistoryController($interactionHistoryService);
            $interactionController->logAccountInteraction();
            break;

        default:
            include __DIR__ . '/../src/views/404.php';  // Load 404 page if no matching route is found
            break;
    }
}

