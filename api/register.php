<?php

require_once("config/core.php");

header("Access-Control-Allow-Methods: POST");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$username = $_POST["username"];
$password = $_POST["password"];

if (!isset($username))
  exitError(400, "Missing 'username' field.");

if (!isset($password))
  exitError(400, "Missing 'password' field.");

if (!preg_match("/^[\p{L}0-9]*$/u", $username))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if (strlen($username) < 3)
  exitError(400, "The username must be at least 3 characters.");

if (36 < strlen($username))
  exitError(400, "The username must be at most 36 characters.");

$user = getUserById($username, $db);

if ($user)
  exitError(409, "Username already exists in the database.");

// Code
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$token = generateToken();
$tokenHash = hash("sha256", $token);
$tokenExpiration = new DateTime("now");
$tokenExpiration->add(new DateInterval("P1D"));

$sql = "
INSERT INTO `User` (username, password_hash, token_hash, token_expiration)
VALUES (:username, :password_hash, :token_hash, :token_expiration);
";

$stmt = $db->prepare($sql);
$stmt->execute([
  ":username" => $username,
  ":password_hash" => $passwordHash,
  ":token_hash" => $tokenHash,
  ":token_expiration" => $tokenExpiration->format("Y-m-d H:i:s")
]);

http_response_code(201);
$res = ["token" => $token];
echo(json_encode($res));