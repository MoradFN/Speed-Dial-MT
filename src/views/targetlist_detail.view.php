<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target List Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Target List: <?= htmlspecialchars($targetList['name']) ?></h1>

    <p>Description: <?= htmlspecialchars($targetList['description']) ?></p>
    <p>Status: <?= htmlspecialchars($targetList['status']) ?></p>
    <p>Assigned To: <?= htmlspecialchars($targetList['assigned_to']) ?></p>

    <h2>Accounts and Contacts</h2>

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
            <?php foreach ($targetList['accounts'] as $account): ?>
                <tr>
                    <td rowspan="<?= count($account['contacts']) + 1 ?>">
                        <strong><?= htmlspecialchars($account['name']) ?></strong>
                    </td>
                </tr>
                <?php foreach ($account['contacts'] as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']) ?></td>
                        <td><?= htmlspecialchars($contact['status']) ?></td>
                        <td><?= htmlspecialchars($contact['job_title']) ?></td>
                        <td><?= htmlspecialchars($contact['phone']) ?></td>
                        <td>
                            <button onclick="startDialer(<?= $contact['id'] ?>)">Start Dialing</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="targetlist.php">Back to Target Lists</a>

    <script>
        function startDialer(contactId) {
            window.location.href = "speeddialer.php?contact=" + contactId;
        }
    </script>
</body>
</html>
