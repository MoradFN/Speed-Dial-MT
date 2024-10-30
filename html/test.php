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


// Capture filter inputs from GET parameters
$filters = [
    'user_name' => $_GET['user_name'] ?? null,

    'campaign_name' => $_GET['campaign_name'] ?? null,
    'campaign_status' => $_GET['campaign_status'] ?? null,
    'campaign_start_date' => $_GET['campaign_start_date'] ?? null,
    'campaign_end_date' => $_GET['campaign_end_date'] ?? null,
    'campaign_description' => $_GET['campaign_description'] ?? null,

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


// Order and direction
$orderBy = $_GET['orderBy'] ?? 'contact_contacted_at';
$direction = $_GET['direction'] ?? 'DESC';

// Call the method to get filtered results
$interactionHistory = $historyAccountInteractionModel->getDetailedInteractionHistory($filters, $orderBy, $direction);
var_dump($route)
?>

<!-- Display the form and results -->
<form method="get">
    <input type="hidden" name="route" value="test">
    <label for="campaign_name">Campaign Name:</label>
    <input type="text" name="campaign_name" id="campaign_name" value="<?= htmlspecialchars($_GET['campaign_name'] ?? '') ?>"><br>

    <label for="account_name">Account Name:</label>
    <input type="text" name="account_name" id="account_name" value="<?= htmlspecialchars($_GET['account_name'] ?? '') ?>"><br>

    <label for="contact_name">Contact Name:</label>
    <input type="text" name="contact_name" id="contact_name" value="<?= htmlspecialchars($_GET['contact_name'] ?? '') ?>"><br>

    <label for="date_field">Date Field:</label>
    <select name="date_field">
        <option value="contact_contacted_at" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_contacted_at' ? 'selected' : '' ?>>Contacted At</option>
        <option value="contact_next_contact_date" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_next_contact_date' ? 'selected' : '' ?>>Next Contact Date</option>
    </select><br>

    <label for="date_from">From Date:</label>
    <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>"><br>

    <label for="date_to">To Date:</label>
    <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>"><br>

    <button type="submit">Filter</button>
</form>

<!-- Display the filtered results -->
<table border="1">
    <thead>
        <tr>
            <th><a href="?route=test&orderBy=campaign_name&direction=<?= ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC' ?>">Campaign Name</a></th>
            <th>Campaign Description</th>
            <th>Campaign Start Date</th>
            <th>Campaign End Date</th>
            <th>Campaign Status</th>
            <th><a href="?route=test&orderBy=target_list_name&direction=<?= ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC' ?>">Target List Name</a></th>
            <th>Target List Description</th>
            <th>User Name</th>
            <th><a href="?route=test&orderBy=account_name&direction=<?= ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC' ?>">Account Name</a></th>
            <th><a href="?route=test&orderBy=contact_name&direction=<?= ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC' ?>">Contact Name</a></th>
            <th>Contact Outcome</th>
            <th>Contact Notes</th>
            <th>Contact Method</th>
            <th><a href="?route=test&orderBy=contact_contacted_at&direction=<?= ($_GET['direction'] ?? 'ASC') === 'ASC' ? 'DESC' : 'ASC' ?>">Contacted At</a></th>
            <th>Next Contact Date</th>
            <th>Interaction Duration</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($interactionHistory as $interaction) : ?>
            <tr>
                <td><?= htmlspecialchars($interaction['campaign_name']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_description']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_start_date']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_end_date']) ?></td>
                <td><?= htmlspecialchars($interaction['campaign_status']) ?></td>
                <td><?= htmlspecialchars($interaction['target_list_name']) ?></td>
                <td><?= htmlspecialchars($interaction['target_list_description']) ?></td>
                <td><?= htmlspecialchars($interaction['user_name']) ?></td>
                <td><?= htmlspecialchars($interaction['account_name']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_name']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_outcome']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_notes']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_method']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_contacted_at']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_next_contact_date']) ?></td>
                <td><?= htmlspecialchars($interaction['contact_interaction_duration']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>