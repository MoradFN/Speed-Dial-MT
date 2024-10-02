<!-- NOT IN USE, Create a more modular aproach later. -->
<!-- NOT IN USE, Create a more modular aproach later. -->
 <!-- NOT IN USE, Create a more modular aproach later. -->
  <!-- NOT IN USE, Create a more modular aproach later. -->
   <!-- NOT IN USE, Create a more modular aproach later. -->
    <!-- NOT IN USE, Create a more modular aproach later. -->
     <!-- NOT IN USE, Create a more modular aproach later. -->
      <!-- NOT IN USE, Create a more modular aproach later. -->
       <!-- NOT IN USE, Create a more modular aproach later. -->
        <!-- NOT IN USE, Create a more modular aproach later. -->
         
<?php
// routes/target-lists.php
$method = $_SERVER['REQUEST_METHOD'];

require '../src/controllers/TargetListController.php';
$controller = new TargetListController();

if ($method === 'GET') {
    switch ($route) {
        case 'target-lists':
            $controller->listAllTargetLists();  // Fetch and display all target lists
            break;
        case 'target-list-detail':
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $controller->showTargetList($id);  // Show details for a specific target list
            break;
        default:
            include 'assets/404.php';  // Route not found
            break;
    }
} elseif ($method === 'POST') {
    switch ($route) {
        case 'add-target-list':
            $controller->createTargetList($_POST);  // Create a new target list via POST
            break;
        default:
            include 'assets/404.php';  // Route not found
            break;
    }
}
