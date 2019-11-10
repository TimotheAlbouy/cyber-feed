<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/FeedUser.php");

header("Access-Control-Allow-Methods: DELETE");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "DELETE")
  exitError(405, "Only DELETE requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);
if (!$user->is_admin())
	exitError(401, "User is not admin")

if (!isset($_GET["id"]))
  exitError(400, "Missing 'id' field.");

$user = new User();
$user->id = $_GET["id"];

if (!$user->exists())
  exitError(404, "The user does not exist.");

// Code
$user->delete();

http_response_code(204);
$res = null;
echo(json_encode($res));