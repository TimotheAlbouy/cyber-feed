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

if (!isset($_GET["id"]))
  exitError(400, "Missing 'id' field.");

$feedUser = new FeedUser();
$feedUser->feed_id = $_GET["id"];
$feedUser->username = $user->username;

if (!$feedUser->exists())
  exitError(404, "The feed does not exist.");

// Code
// TODO : delete Feed if it is not used anymore
$feedUser->delete();

http_response_code(204);
$res = null;
echo(json_encode($res));