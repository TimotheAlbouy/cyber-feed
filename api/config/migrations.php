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
  url VARCHAR(255) PRIMARY KEY
);
";

$migrations["FeedUser"] = "
CREATE OR REPLACE TABLE `FeedUser` (
  url VARCHAR(255) REFERENCES `Feed`(url),
  username VARCHAR(255) REFERENCES `User`(username),
  PRIMARY KEY(username, url)
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