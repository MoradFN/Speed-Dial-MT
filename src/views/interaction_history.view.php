<?php
// Include the header navigation
include __DIR__ . '/header.php';



// Capture filter inputs from GET parameters
$interactionHistory = $viewData['interactionHistory'];
$totalPages = $viewData['totalPages'];
$totalRecords = $viewData['totalRecords'];
$page = $viewData['page'];
$limit = $viewData['limit'];
$filters = $viewData['filters'];
$orderBy = $viewData['orderBy'];
$direction = $viewData['direction'];

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


// // var_dump($route);
// echo '<pre>';
// print_r('GET: ' . http_build_query($_GET));
// echo '</pre>';
// var_dump($filters);
// Pagination links (Example: Next and Previous) //MTTODO - PAGINATION
$nextPage = $page < $totalPages ? $page + 1 : $totalPages;
$prevPage = $page > 1 ? $page - 1 : 1;

// Retain filter and sorting parameters in pagination URLs
$nextPageUrl = "?" . http_build_query(array_merge($_GET, ['page' => $nextPage]));
$prevPageUrl = "?" . http_build_query(array_merge($_GET, ['page' => $prevPage]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interaction History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    


<div class="container-fluid">

    <form method="get" class="mb">
        <input type="hidden" name="route" value="interaction-history">

        <!-- First Row (Campaign Fields) -->
        <div class="row">
            <div class="form-group col-md-4">
                <label for="campaign_name">Campaign Name:</label>
                <input type="text" class="form-control" name="campaign_name" id="campaign_name" value="<?= htmlspecialchars($_GET['campaign_name'] ?? '') ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="campaign_status">Campaign Status:</label>
                <select name="campaign_status" id="campaign_status" class="form-control">
                    <option value="">Select a status</option>
                    <?php foreach ($campaignStatusOptions as $value => $label) : ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= (isset($_GET['campaign_status']) && $_GET['campaign_status'] === $value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="target_list_name">Target List Name:</label>
                <input type="text" name="target_list_name" id="target_list_name" class="form-control" value="<?= htmlspecialchars($_GET['target_list_name'] ?? '') ?>">
            </div>
        </div>

        <!-- Second Row (Account and Contact Fields) -->
        <div class="row">
            <div class="form-group col">
                <label for="account_name">Account Name:</label>
                <input type="text" class="form-control" name="account_name" id="account_name" value="<?= htmlspecialchars($_GET['account_name'] ?? '') ?>">
            </div>
            <div class="form-group col">
                <label for="contact_name">Contact Name:</label>
                <input type="text" class="form-control" name="contact_name" id="contact_name" value="<?= htmlspecialchars($_GET['contact_name'] ?? '') ?>">
            </div>
            <div class="form-group col">
                <label for="contact_phone">Contact Phone:</label>
                <input type="text" class="form-control" name="contact_phone" id="contact_phone" value="<?= htmlspecialchars($_GET['contact_phone'] ?? '') ?>">
            </div>
            <div class="form-group col">
                <label for="contact_interaction_outcome">Contact Outcome:</label>
                <select name="contact_interaction_outcome" id="contact_interaction_outcome" class="form-control">
                    <option value="">Select an outcome</option>
                    <?php foreach ($contactInteractionOutcomeOptions as $value => $label) : ?>
                        <option value="<?= htmlspecialchars($value) ?>" <?= (isset($_GET['contact_interaction_outcome']) && $_GET['contact_interaction_outcome'] === $value) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Third Row (Outcome and Date Fields) -->
        <div class="row">

            <div class="form-group col-md-4">
                <label for="date_field">Date Field:</label>
                <select name="date_field" id="date_field" class="form-control">
                    <option value="contact_contacted_at" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_contacted_at' ? 'selected' : '' ?>>Contacted At</option>
                    <option value="contact_next_contact_date" <?= isset($_GET['date_field']) && $_GET['date_field'] === 'contact_next_contact_date' ? 'selected' : '' ?>>Next Contact Date</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="date_from">From Date:</label>
                <input type="date" class="form-control" name="date_from" id="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>">
            </div>
            <div class="form-group col-md-4">
                <label for="date_to">To Date:</label>
                <input type="date" class="form-control" name="date_to" id="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>">
            </div>
        </div>

        <!-- Button Row -->
        <div class="row">
            <div class="form-group col-md-12 text-center">
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <a href="?route=interaction-history" class="btn btn-secondary">Clear Filters</a>
            </div>
        </div>
    </form>
</div>



    <!-- Display the filtered results -->
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'user_name', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">User Name</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_name', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Campaign Name</a></th>
                <th>Campaign Description</th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_start_date', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Campaign Start Date</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_end_date', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Campaign End Date</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'campaign_status', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Campaign Status</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'target_list_name', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Target List Name</a></th>
                <th>Target List Description</th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'account_name', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Account Name</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_name', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Contact Name</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_interaction_outcome', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Contact Outcome</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_phone', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Contact Phone</a></th>
                <th>Contact Notes</th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_contacted_at', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Contacted At</a></th>
                <th><a href="?<?= http_build_query(array_merge($_GET, ['orderBy' => 'contact_next_contact_date', 'direction' => ($direction === 'ASC' ? 'DESC' : 'ASC')])) ?>">Next Contact Date</a></th>
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
</div>
<!-- //MTTODO - PAGINATION -->
 <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $prevPageUrl ?>" tabindex="-1">Previous</a>
        </li>
        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $nextPageUrl ?>">Next</a>
        </li>

    </ul>
</nav>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


        
            


