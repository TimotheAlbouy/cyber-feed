<?php

require_once("config/core.php");

header("Access-Control-Allow-Methods: POST");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

if (!isset($_POST["username"]) || !isset($_POST["password"]))
  exitError(400, "Missing 'username' or 'password' fields.");

$username = $_POST["username"];
$password = $_POST["password"];

if (!preg_match("/^[\p{L}0-9]*$/u", $username))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if (strlen($username) < 3)
  exitError(400, "The username must be at least 3 characters.");

if (36 < strlen($username))
  exitError(400, "The username must be at most 36 characters.");

if (usernameExists($username, $db))
  exitError(409, "Username already exists in the database.");

// Code
$hashedPassword = password_hash($password, true);
$token = generateToken();
$tokenExpiration = new DateTime("now");
$tokenExpiration->add(new DateInterval("P1D"));

$sql = "
INSERT INTO User (username, hashed_password, token, token_expiration)
VALUES (:username, :hashed_password, :token, :token_expiration);
";

$stmt = $db->prepare($sql);
$stmt->execute([
  ":username" => $username,
  ":hashed_password" => $hashedPassword,
  ":token" => $token,
  ":token_expiration" => $tokenExpiration->format("Y-m-d H:i:s")
]);

http_response_code(201);
$res = ["token" => $token];
echo(json_encode($res));