<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Lists</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS file -->
</head>
<body>
    <h1>All Target Lists</h1>

    <?php if (empty($targetLists)): ?>
        <p>No target lists available.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Target List Name</th>
                    <th>Description</th>
                    <th>Campaign</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($targetLists as $targetList): ?>
                    <tr>
                        <td><?= htmlspecialchars($targetList['name']) ?></td>
                        <td><?= htmlspecialchars($targetList['description']) ?></td>
                        <td><?= htmlspecialchars($targetList['campaign_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($targetList['status']) ?></td>
                        <td><?= htmlspecialchars($targetList['assigned_to'] ?? 'Unassigned') ?></td>
                        <td>
                        <td><a href="?route=target-list-detail&id=<?= $targetList['id'] ?>">View Details</a></td>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
