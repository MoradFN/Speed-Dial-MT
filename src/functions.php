<?php
// Sample function to sanitize user input
function sanitize($data) {
  return htmlspecialchars(strip_tags(trim($data)));
}

// Example function to connect to MySQL
function db_connect() {
  $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
  try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}
