<?php

require_once("Database.php");

// Initialize the database connection
$db = Database::getInstance();

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
  feed_id INT,
  FOREIGN KEY (feed_id) REFERENCES `Feed`(id) ON DELETE CASCADE,
  username VARCHAR(36),
  FOREIGN KEY (username) REFERENCES `User`(username) ON DELETE CASCADE,
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