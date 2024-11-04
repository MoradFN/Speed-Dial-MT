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
$interactionHistoryService = new InteractionHistoryService($historyAccountInteractionModel, $historyContactInteractionModel, $accountContactRelationModel);

$interactionHistoryController = new InteractionHistoryController($interactionHistoryService);



// Capture page and limit, defaulting to 1 and 10 if not provided
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; //MTTODO - PAGINATION
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Capture filter inputs from GET parameters
$filters = [
    'user_name' => $_GET['user_name'] ?? null,

    'campaign_name' => $_GET['campaign_name'] ?? null,
    'campaign_start_date' => $_GET['campaign_start_date'] ?? null,
    'campaign_end_date' => $_GET['campaign_end_date'] ?? null,
    'campaign_status' => $_GET['campaign_status'] ?? null,
    

    'target_list_name' => $_GET['target_list_name'] ?? null,
    'target_list_description' => $_GET['target_list_description'] ?? null,

    'account_name' => $_GET['account_name'] ?? null,

    'contact_name' => $_GET['contact_name'] ?? null,
    'contact_interaction_outcome' => $_GET['contact_interaction_outcome'] ?? null,
    'contact_phone' => $_GET['contact_phone'] ?? null,
    'contact_notes' => $_GET['contact_notes'] ?? null,

    'contact_contacted_at' => $_GET['contact_contacted_at'] ?? null,
    'contact_next_contact_date' => $_GET['contact_next_contact_date'] ?? null,
    'contact_interaction_duration' => $_GET['contact_interaction_duration'] ?? null,



    'date_field' => $_GET['date_field'] ?? null,// To indicate which date field to filter
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null,
];

$campaignStatusOptions = [
    'Active' => 'Active',
    'Completed' => 'Completed',
    'Pending' => 'Pending',
    'Paused' => 'Paused',
    'Upcoming' => 'Upcoming',
    'Inactive' => 'Inactive'
];

$contactInteractionOutcomeOptions = [
    'Interested' => 'Interested',
    'No Answer' => 'No Answer',
    'Busy' => 'Busy',
    'Successful' => 'Successful',
    'Unsuccessful' => 'Unsuccessful',
    'Not Interested' => 'Not Interested'
];

// Order and direction
$orderBy = $_GET['orderBy'] ?? 'contact_contacted_at';
$direction = $_GET['direction'] ?? 'DESC';

// Call the method to get filtered results
$response = $interactionHistoryController->showInteractionHistory($filters, $orderBy, $direction, $page, $limit);
$interactionHistory = $response['interactionHistory'];
$totalPages = $response['totalPages'];
$totalRecords = $response['totalRecords'];
var_dump($route);

// Pagination links (Example: Next and Previous) //MTTODO - PAGINATION
$nextPage = $page < $totalPages ? $page + 1 : $totalPages;
$prevPage = $page > 1 ? $page - 1 : 1;

// Retain filter and sorting parameters in pagination URLs
$queryParams = http_build_query(array_merge($_GET, ['page' => $nextPage])); //MTTODO - PAGINATION
$nextPageUrl = "?$queryParams";

$queryParams = http_build_query(array_merge($_GET, ['page' => $prevPage]));
$prevPageUrl = "?$queryParams";
?>



<!-- Display the form and results -->
<form method="get">
    <input type="hidden" name="route" value="test">
    <label for="campaign_name">Campaign Name:</label>
    <input type="text" name="campaign_name" id="campaign_name" value="<?= htmlspecialchars($_GET['campaign_name'] ?? '') ?>"><br>


    <!-- Campaign Status Dropdown -->
    <label for="campaign_status">Campaign Status:</label>
    <select name="campaign_status" id="campaign_status">
        <option value="">Select a status</option> <!-- Default option -->
        <?php foreach ($campaignStatusOptions as $value => $label) : ?>
            <option value="<?= htmlspecialchars($value) ?>" <?= (isset($_GET['campaign_status']) && $_GET['campaign_status'] === $value) ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select><br>



    <label for="account_name">Account Name:</label>
    <input type="text" name="account_name" id="account_name" value="<?= htmlspecialchars($_GET['account_name'] ?? '') ?>"><br>

    <label for="contact_name">Contact Name:</label>
    <input type="text" name="contact_name" id="contact_name" value="<?= htmlspecialchars($_GET['contact_name'] ?? '') ?>"><br>

    <!-- Contact Interaction Outcome Dropdown -->
    <label for="contact_interaction_outcome">Contact Interaction Outcome:</label>
    <select name="contact_interaction_outcome" id="contact_interaction_outcome">
        <option value="">Select an outcome</option> <!-- Default option -->
        <?php foreach ($contactInteractionOutcomeOptions as $value => $label) : ?>
            <option value="<?= htmlspecialchars($value) ?>" <?= (isset($_GET['contact_interaction_outcome']) && $_GET['contact_interaction_outcome'] === $value) ? 'selected' : '' ?>>
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label for="contact_phone">Contact Phone:</label>
    <input type="text" name="contact_phone" id="contact_phone" value="<?= htmlspecialchars($_GET['contact_phone'] ?? '') ?>"><br>
    

    <label for="date_field">Date Field:</label>
    <select name="date_field">
        <option value="contact_contacted_at" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_contacted_at' ? 'selected' : '' ?>>Contacted At</option>
        <option value="contact_next_contact_date" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_next_contact_date' ? 'selected' : '' ?>>Next Contact Date</option>
    </select><br>

    <label for="target_list_name">Target List Name:</label>
    <input type="text" name="target_list_name" id="target_list_name" value="<?= htmlspecialchars($_GET['target_list_name'] ?? '') ?>"><br>

    <label for="date_from">From Date:</label>
    <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"><br>

    <label for="date_to">To Date:</label>
    <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"><br>

    <button type="submit">Filter</button>
    <a href="?route=test" class="clear-filters">Clear Filters</a> 
</form>

<!-- Display the filtered results -->
<table border="1">
    <thead>
        <tr>
        <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'user_name', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">User Name?</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_name', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Campaign Name</a></th>
            <th>Campaign Description</th>

            
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_start_date', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Campaign Start Date</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_end_date', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Campaign End Date</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_status', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Campaign Status</a></th>


            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'target_list_name', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Target List Name</a></th>
            <th>Target List Description</th>
            

            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'account_name', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Account Name</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_name', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Contact Name</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_interaction_outcome', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Contact Outcome</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_phone', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Contact Phone</a></th>
            <th>Contact Notes</th>


            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_contacted_at', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Contacted At</a></th>
            <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_next_contact_date', 'direction' => ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC'])) ?>">Next Contact Date</a></th>
            <th>Interaction Duration</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($interactionHistory as $interaction) : ?>
            <tr>
                <td><?= htmlspecialchars($interaction['user_name']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_name']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_description']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_start_date']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_end_date']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_status']) ?></td>
                <td><?= htmlspecialchars($interaction['target_list_name']) ?></td>
                <td><?= htmlspecialchars($interaction['target_list_description']) ?></td>
                <td><?= htmlspecialchars($interaction['account_name']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_name']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_interaction_outcome']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_phone']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_notes'] ?? '') ?></td>
                <td><?= htmlspecialchars($interaction['contact_contacted_at']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_next_contact_date'] ?? '') ?></td>
                <td><?= htmlspecialchars($interaction['contact_interaction_duration'] ?? '') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<!-- //MTTODO - PAGINATION -->
<div class="pagination">
    <a href="<?= $prevPageUrl ?>">Previous</a> 
    <a href="<?= $nextPageUrl ?>">Next</a>
</div>

