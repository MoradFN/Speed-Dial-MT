<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target List Details</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS file -->
</head>
<body>
    <h1>Target List: <?= htmlspecialchars($targetList['name']) ?></h1>
    <p>Description: <?= htmlspecialchars($targetList['description']) ?></p>

    <?php if (empty($targetList['accounts'])): ?>
        <p>No accounts available in this target list.</p>
    <?php else: ?>
        <h2>Accounts and Contacts</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Contact Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($targetList['accounts'] as $account): ?>
                    <tr>
                        <td rowspan="<?= count($account['contacts']) + 1 ?>"><strong><?= htmlspecialchars($account['name']) ?></strong></td>
                    </tr>
                    <?php foreach ($account['contacts'] as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['first_name']) ?> <?= htmlspecialchars($contact['last_name']) ?></td>
                            <td><?= htmlspecialchars($contact['phone']) ?></td>
                            <td><?= htmlspecialchars($contact['email']) ?></td>
                            <td><?= htmlspecialchars($contact['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="targetlist.php">Back to all target lists</a> <!-- Link back to the main target list view -->
</body>
</html>
