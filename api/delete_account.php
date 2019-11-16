<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/FeedUser.php");

header("Access-Control-Allow-Methods: POST");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

if (!isset($_POST["password"]))
  exitError(400, "Missing 'password' field.");

$password = $_POST["password"];

if (!password_verify($password, $user->password_hash))
  exitError(401, "Password incorrect.");

// Code
$user->delete();

http_response_code(204);
$res = null;
echo(json_encode($res));