<?php
//config/config.php
require_once __DIR__ . '/../vendor/autoload.php';

// Show errors for development
ini_set("display_errors", 1);
error_reporting(E_ALL);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__); 
$dotenv->load();

// Correctly access the environment variables
$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Establish a MySQLi database connection
$db = new mysqli($host, $username, $password, $dbname);



// Check if the connection was successful
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}
