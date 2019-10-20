<?php

require_once("config/core.php");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$username = $_POST["username"];
$password = $_POST["password"];

if (!isset($username))
  exitError(400, "Missing 'username' field.");

if (!isset($password))
  exitError(400, "Missing 'password' field.");

$user = getUserById($username, $db);

if (!$user)
  exitError(401, "Username does not exist.");

if (!password_verify($password, $user["password_hash"]))
  exitError(401, "Password incorrect.");

// Code
$token = generateToken();
$tokenHash = hash("sha256", $token);
$tokenExpiration = new DateTime("now");
$tokenExpiration->add(new DateInterval("P1D"));

$sql = "
UPDATE `User`
SET token_hash = :token_hash,
    token_expiration = :token_expiration
WHERE username = :username;
";

$stmt = $db->prepare($sql);
$stmt->execute([
  ":username" => $username,
  ":token_hash" => $token,
  ":token_expiration" => $tokenExpiration->format("Y-m-d H:i:s")
]);

http_response_code(200);
$res = ["token" => $token];
echo(json_encode($res));