<!-- interaction_history.view.php -->
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
    <h1>Interaction History for Account ID: <?= htmlspecialchars($accountId) ?></h1>
    
    <!-- Display account interaction history -->
    <?php if (!empty($interactionHistory['account_history'])): ?>
        <h2>Account Interactions</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Outcome</th>
                    <th>Notes</th>
                    <th>Next Contact Date</th>
                    <th>Related Contact Interaction</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($interactionHistory['account_history'] as $accountInteraction): ?>
                    <tr>
                        <td><?= htmlspecialchars($accountInteraction['contacted_at']) ?></td>
                        <td><?= htmlspecialchars($accountInteraction['outcome']) ?></td>
                        <td><?= htmlspecialchars($accountInteraction['notes']) ?></td>
                        <td><?= htmlspecialchars($accountInteraction['next_contact_date']) ?></td>
                        <td>
                            <?php if (!empty($accountInteraction['related_contact_interaction'])): ?>
                                Contact ID: <?= htmlspecialchars($accountInteraction['related_contact_interaction']['contact_id']) ?>
                                <br>
                                Outcome: <?= htmlspecialchars($accountInteraction['related_contact_interaction']['outcome']) ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No interactions found for this account.</p>
    <?php endif; ?>
</body>
</html>
