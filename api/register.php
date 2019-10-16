<?php

require_once("api-common.php");

// Guard clauses

if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$username = $POST["username"];
$password = $POST["password"];

if (!isset($username) || !isset($password))
  exitError(400, "Missing 'username' or 'password' fields.");

if (ctype_alnum($username))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if (false)
  exitError(409, "Username already exists in the database.");

// Code

$hashed_password = password_hash($password);

$sql = "INSERT INTO Users (username, hashed_password) VALUES (:username, :hashed_password);";
$stmt = $db->prepare($sql);
$stmt->execute([
  ":username" => $username,
  ":hashed_password" => $hashed_password
]);

http_response_code(201);
echo("gneugneu");