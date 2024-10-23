<?php
include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interaction History</title>
</head>
<body>
    <h1>All Interactions</h1>

    <?php if (!empty($accountInteractions)) : ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Account Name</th>
                    <th>Contact Name</th>
                    <th>Outcome</th>
                    <th>Notes</th>
                    <th>Contact Method</th>
                    <th>Next Contact Date</th>
                    <th>Contacted At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accountInteractions as $interaction) : ?>
                    <tr>
                        <td><?= htmlspecialchars($interaction['account_name']) ?></td>
                        <td>
                            <?php if (!empty($interaction['related_contact_interaction'])): ?>
                                <?= htmlspecialchars($interaction['related_contact_interaction']['first_name'] . ' ' . $interaction['related_contact_interaction']['last_name']) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($interaction['outcome']) ?></td>
                        <td><?= htmlspecialchars($interaction['notes']) ?></td>
                        <td><?= htmlspecialchars($interaction['contact_method']) ?></td>
                        <td><?= htmlspecialchars($interaction['next_contact_date']) ?></td>
                        <td><?= htmlspecialchars($interaction['contacted_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No interactions found.</p>
    <?php endif; ?>
</body>
</html>
