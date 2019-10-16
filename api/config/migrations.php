<?php

require_once("database.php");

$migrations = [];

$migrations["User"] = "
CREATE OR REPLACE TABLE `User` (
  username VARCHAR(255) PRIMARY KEY,
  hashed_password VARCHAR(255),
  is_admin BOOLEAN
);
";

$migrations["Feed"] = "
CREATE OR REPLACE TABLE `Feed` (
  url VARCHAR(255) PRIMARY KEY
);
";

$migrations["FeedUser"] = "
CREATE OR REPLACE TABLE `FeedUser` (
  username VARCHAR(255),
  url VARCHAR(255),
  PRIMARY KEY(username, url)
);
";

foreach ($migrations as $name => $migration) {
  try {
    $db->exec($migration);
    echo("Table " . $name . " migrated.<br>");
  } catch (PDOException $e) {
    echo("Error during " . $name . " migration: " . $e->getMessage() . "<br>");
  }
}