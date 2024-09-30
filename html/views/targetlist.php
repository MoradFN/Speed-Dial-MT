<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/models/AccountModel.php';
require_once __DIR__ . '/../src/models/ContactModel.php';
require_once __DIR__ . '/../src/models/TargetListModel.php';
require_once __DIR__ . '/../services/SpeedDialerService.php';

// Create database connection
$db = new Database();

// Instantiate models
$accountModel = new AccountModel($db);
$contactModel = new ContactModel($db);
$targetListModel = new TargetListModel($db);

// Instantiate the service
$speedDialerService = new SpeedDialerService($accountModel, $contactModel, $targetListModel);

// Fetch the target list, accounts, and contacts
$targetListId = 1; // Example target list ID
$targetListData = $speedDialerService->getAccountsAndContactsForTargetList($targetListId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target List Overview</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Target List: <?= $targetListData['name'] ?></h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>Account</th>
                <th>Contact Name</th>
                <th>Status</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($targetListData['accounts'] as $account): ?>
                <tr>
                    <td rowspan="<?= count($account['contacts']) + 1 ?>"><strong><?= $account['name'] ?></strong></td>
                </tr>
                <?php foreach ($account['contacts'] as $contact): ?>
                    <tr>
                        <td><?= $contact['name'] ?></td>
                        <td><?= $contact['status'] ?></td>
                        <td><?= $contact['role'] ?></td>
                        <td><?= $contact['phone'] ?></td>
                        <td><button onclick="startDialer(<?= $contact['id'] ?>)">Start Dialing</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Start the speed dialer (this could trigger a modal or new page)
        function startDialer(contactId) {
            // Redirect to the speed dialer page or open a modal
            window.location.href = "speeddialer.php?contact=" + contactId;
        }
    </script>
</body>
</html>
