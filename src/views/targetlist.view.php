<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target Lists</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>All Target Lists</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Campaign</th>
                <th>Assigned To</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($targetLists as $list): ?>
                <tr>
                    <td><?= htmlspecialchars($list['id']) ?></td>
                    <td><?= htmlspecialchars($list['name']) ?></td>
                    <td><?= htmlspecialchars($list['description']) ?></td>
                    <td><?= htmlspecialchars($list['status']) ?></td>
                    <td><?= htmlspecialchars($list['campaign_name']) ?></td>
                    <td><?= htmlspecialchars($list['assigned_to']) ?></td>
                    <td>
                        <a href="targetlist_detail.php?id=<?= $list['id'] ?>">View</a> | 
                        <a href="edit_targetlist.php?id=<?= $list['id'] ?>">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="create_targetlist.php">Create New Target List</a>
</body>
</html>
