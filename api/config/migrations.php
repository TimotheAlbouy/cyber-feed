<?php

require_once("env.php");

// Initialize the database connection
$db = new PDO(
  "mysql:host=" . getenv("host") . ";dbname=" . getenv("dbname"),
  getenv("username"),
  getenv("password")
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Define the migrations
$migrations = [];

$migrations["User"] = "
CREATE OR REPLACE TABLE `User` (
  username VARCHAR(36) PRIMARY KEY,
  password_hash VARCHAR(64) NOT NULL,
  is_admin BOOLEAN DEFAULT FALSE NOT NULL,
  token VARCHAR(32) NOT NULL,
  token_expiration DATETIME NOT NULL
);
";

$migrations["Feed"] = "
CREATE OR REPLACE TABLE `Feed` (
  id INT PRIMARY KEY AUTO_INCREMENT,
  url VARCHAR(255) NOT NULL UNIQUE
);
";

$migrations["FeedUser"] = "
CREATE OR REPLACE TABLE `FeedUser` (
  feed_id VARCHAR(255) REFERENCES `Feed`(id),
  username VARCHAR(255) REFERENCES `User`(username),
  PRIMARY KEY(username, feed_id)
);
";

foreach ($migrations as $name => $migration) {
  try {
    $db->exec($migration);
    echo("Table " . $name . " migrated.");
    echo("<br><br>");
  } catch (PDOException $e) {
    echo("Error during " . $name . " migration: " . $e->getMessage() . "<br>");
  }
}