<?php

require_once("api-common.php");

header("Access-Control-Allow-Methods: POST");

// Guard clauses

if (!isset($_POST["username"]) || !isset($_POST["password"]))
  exitError(400, "Missing 'username' or 'password' fields.");

if (ctype_alnum($_POST["username"]))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if (false)
  exitError(409, "Username already exists in the database.");

// Code

$username = $_POST["username"];
$password = $_POST["password"];
$hashed_password = password_hash($password, true);

$sql = "INSERT INTO User (username, hashed_password) VALUES (:username, :hashed_password);";
$stmt = $db->prepare($sql);
$stmt->execute([
  ":username" => $username,
  ":hashed_password" => $hashed_password
]);

http_response_code(201);

$token = "gneugneu";
$res = [
  "token" => $token
];
$jsonRes = json_encode($res);
echo($jsonRes);
