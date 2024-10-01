<?php
// html/test_targetlist.php
require_once __DIR__ . '/../config/config.php'; // This includes the $db variable
require_once __DIR__ . '/../src/models/TargetListModel.php';

// Instantiate the TargetList model, passing the $db connection from config.php
$targetListModel = new TargetListModel($db);

// Test fetching all target lists
$targetLists = $targetListModel->getAllTargetLists();
echo "<pre>";
// print_r($targetLists); // This will print the list of target lists in a readable format
echo "</pre>";

// Hämtar alla accounts och contacts och binder ihop kontakt till varje account med hjälp av target_relation_table
$targetListId = 1; // Assume this ID exists
$accountsAndContacts = $targetListModel->getAccountsAndContactsByTargetList($targetListId);
echo "<pre>";
print_r($accountsAndContacts); // This will print the accounts and contacts in the list
echo "</pre>";
