<!-- src/views/targetlist.view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target List Overview</title>
    <link rel="stylesheet" href="/path/to/style.css">
</head>
<body>
    <h1>Target List: <?= htmlspecialchars($targetListData['name']) ?></h1>
    
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
                    <td rowspan="<?= count($account['contacts']) + 1 ?>"><strong><?= htmlspecialchars($account['name']) ?></strong></td>
                </tr>
                <?php foreach ($account['contacts'] as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></td>
                        <td><?= htmlspecialchars($contact['status']) ?></td>
                        <td><?= htmlspecialchars($contact['job_title']) ?></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td><button onclick="startDialer(<?= $contact['id'] ?>)">Start Dialing</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        function startDialer(contactId) {
            window.location.href = "/speeddialer.php?contact=" + contactId;
        }
    </script>
</body>
</html>
