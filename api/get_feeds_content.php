<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/FeedUser.php");

header("Access-Control-Allow-Methods: GET");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "GET")
  exitError(405, "Only GET requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

// Code
$feedsList = FeedUser::findByUsername($user->username);

$res = [];
foreach ($feedsList as $feed) {
  $xml = @simplexml_load_file($feed->url);
  if ($xml) {
    //exitError(400, "Invalid feed (URL invalid or not following the XML format).");
  }
}

http_response_code(200);
echo(json_encode($res));