<?php

require_once("config/core.php");
require_once("model/User.php");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$username = $_POST["username"];
$password = $_POST["password"];

if (!isset($username))
  exitError(400, "Missing 'username' field.");

if (!isset($password))
  exitError(400, "Missing 'password' field.");

$user = User::findById($username);

if (!$user)
  exitError(401, "Username does not exist.");

if (!password_verify($password, $user->password_hash))
  exitError(401, "Password incorrect.");

// Code
$user->updateToken();

http_response_code(200);
$res = [
  "username" => $username,
  "token" => $user->token,
  "isAdmin" => boolval($user->is_admin)
];
echo(json_encode($res));