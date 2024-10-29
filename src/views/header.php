<nav>
    <ul>
        <li><a href="?route=home">Home</a></li>
        <li><a href="?route=target-lists">Target Lists</a></li>
        <li><a href="?route=interaction-history">History</a></li>
        <li><a href="index.php?route=interaction-history&account_id=1">View account 1 history</a></li>
        <li><a href="index.php?route=interaction-history&account_id=2">View account 2 history</a></li>
        <li><a href="index.php?route=interaction-history&account_id=3">View account 3 history</a></li>
        <li><a href="?route=test">Test</a></li>
    </ul>
</nav>

<?php
echo '<pre>';
var_dump($_GET);
var_dump($_POST);
var_dump($_SESSION);
debug_print_backtrace();
echo '</pre>';

