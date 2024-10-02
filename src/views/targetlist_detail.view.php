<?php
// src/views/targetlist.view.php

// Include the header navigation
include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Target List Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    

    
</head>
<body>
     <!-- //////////////////MODAL 2 START//////////////////////// -->
<!-- Button to trigger the modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#interactionModal">
    Open Speed Dialer
</button>

<!-- Modal Structure -->
<div class="modal fade" id="interactionModal" tabindex="-1" aria-labelledby="interactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAccountName"></h5> <!-- Account Name will be shown here -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Account Info -->
                <div class="container">
                    <!-- Row for Address and Website -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Address:</strong> <span id="modalAccountAddress"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Website:</strong> <span id="modalAccountWebsite"></span></p>
                        </div>
                    </div>

                    <!-- Row for Email and Phone -->
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> <span id="modalAccountEmail"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> <span id="modalAccountPhone"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Contact Info (Collapsible) -->
                <div id="modalContacts"></div> <!-- This will be populated with multiple contacts' details -->

                <!-- Call Logging Form (Collapsible) -->
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseForm" aria-expanded="false" aria-controls="collapseForm">
                    Show/Hide Call Logging Form
                </button>
                <div id="collapseForm" class="collapse">
                    <form id="interactionForm">
                        <!-- Outcome and Notes -->
                        <div class="form-group">
                            <label for="outcome">Call Outcome</label>
                            <input type="text" class="form-control" id="outcome" placeholder="Outcome (e.g., successful, busy)">
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" id="notes" rows="3" placeholder="Enter any notes"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="nextContact">Next Contact Date</label>
                            <input type="datetime-local" class="form-control" id="nextContact">
                        </div>
                        <div class="form-group">
                            <label for="duration">Call Duration (seconds)</label>
                            <input type="number" class="form-control" id="duration" placeholder="Enter call duration">
                        </div>
                        <button type="button" class="btn btn-primary" id="logInteractionBtn">Log Interaction</button>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="previousTargetBtn">Previous Target</button>
                <button type="button" class="btn btn-primary" id="nextTargetBtn">Next Target</button>
            </div>
        </div>
    </div>
</div>








    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    

    <!-- //////////////////MODAL END//////////////////////// -->
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
                        <td rowspan="<?= count($account['contacts']) + 1 ?>"><strong><?= htmlspecialchars($account['account_name']) ?></strong></td>
                    </tr>
                    <?php foreach ($account['contacts'] as $contact): ?>
                        <tr>
                            <td><?= htmlspecialchars($contact['first_name']) ?> <?= htmlspecialchars($contact['last_name']) ?></td>
                            <td><?= htmlspecialchars($contact['contact_phone']) ?></td>
                            <td><?= htmlspecialchars($contact['contact_email']) ?></td>
                            <td><?= htmlspecialchars($contact['contact_status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
    <a href="?route=target-lists">Back to all target lists</a>
    <script>
    // Pass the PHP account data to JavaScript
    const accounts = <?= json_encode($targetList['accounts']) ?>;
</script>
<script src="/assets/js/script.js"></script>
</body>
</html>
