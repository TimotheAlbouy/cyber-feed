<?php

require_once("config/core.php");

require_once("model/User.php");

header("Access-Control-Allow-Methods: GET");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "GET")
  exitError(405, "Only GET requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

if (!$user->is_admin)
  exitError(403, "The provided token is not admin.");
  
// Code
http_response_code(200);
$res = ["users" => User::all()];
echo(json_encode($res));