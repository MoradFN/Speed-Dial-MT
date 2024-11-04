<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Header</title>
</head>
<body>
    
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Dashboard</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="?route=home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="?route=target-lists">Target Lists</a></li>
            <li class="nav-item"><a class="nav-link" href="?route=interaction-history">History</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?route=interaction-history-account">View Accounts History</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?route=interaction-history&account_id=1">View Account 1 History</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?route=interaction-history&account_id=2">View Account 2 History</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?route=interaction-history&account_id=3">View Account 3 History</a></li>
            <li class="nav-item"><a class="nav-link" href="?route=test">Test</a></li>
        </ul>
    </div>
</nav>

<!-- <div class="container"> -->
    <?php
    // echo '<pre>';
    // var_dump($_GET);
    // var_dump($_POST);
    // echo '</pre>';
    ?>
<!-- </div> -->

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
