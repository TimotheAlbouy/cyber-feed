<?php

require_once("config/core.php");

require_once("model/User.php");

header("Access-Control-Allow-Methods: POST");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

if (!isset($_POST["username"]))
  exitError(400, "Missing 'username' field.");

if (!isset($_POST["password"]))
  exitError(400, "Missing 'password' field.");

$username = $_POST["username"];
$password = $_POST["password"];

if (!preg_match("/^[\p{L}0-9]*$/u", $username))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if (strlen($username) < 3)
  exitError(400, "The username must be at least 3 characters.");

if (36 < strlen($username))
  exitError(400, "The username must be at most 36 characters.");

$user = User::findById($username);

if ($user)
  exitError(409, "Username already exists in the database.");

// Code
$user = new User();

$expirationDate = new DateTime("now");
$expirationDate->add(new DateInterval("P1D"));
$token_expiration = $expirationDate->format("Y-m-d H:i:s");

$user->username = $username;
$user->is_admin = false;
$user->password_hash = password_hash($password, PASSWORD_DEFAULT);
$user->token = generateToken();
$user->token_expiration = $token_expiration;

$user->create();

http_response_code(201);
$res = [
  "username" => $username,
  "token" => $user->token
];
echo(json_encode($res));