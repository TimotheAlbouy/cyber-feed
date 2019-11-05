<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/Feed.php");
require_once("model/FeedUser.php");

header("Access-Control-Allow-Methods: POST");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

if (!isset($_POST["url"]))
  exitError(400, "Missing 'url' field.");

$url = $_POST["url"];

$xml = @simplexml_load_file($url);
if (!$xml)
  exitError(400, "Invalid feed (URL invalid or not following the XML format).");

// Code
$feed = new Feed();
$feed->url = $url;
if (!$feed->exists())
  $feed->create();
$feed = Feed::findByUrl($url);

$feedUser = new FeedUser();
$feedUser->feed_id = $feed->id;
$feedUser->username = $user->username;
if ($feedUser->exists())
  exitError(409, "The feed already exists in the user's list.");
$feedUser->create();

http_response_code(204);
$res = null;
echo(json_encode($res));