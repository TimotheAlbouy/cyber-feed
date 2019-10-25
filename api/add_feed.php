<?php

require_once("config/core.php");
require_once("model/User.php");

header("Access-Control-Allow-Methods: POST");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

$url = $_POST["url"];
if (!isset($url))
  exitError(400, "Missing 'url' field.");

if (!filter_var($url, FILTER_VALIDATE_URL))
  exitError(400, "The provided string does not follow the URL format.");

$xml = @simplexml_load_file($url);
if (!$xml)
  exitError(400, "The given feed does not respond.");

$xmlErrors = libxml_get_errors();
if ($xmlErrors)
  exitError(400, "The given feed does not follow the XML format.");

// Code
require_once("model/Feed.php");
require_once("model/FeedUser.php");

$feed = new Feed();
$feed->url = $url;

if (!$feed->exists())
  $feed->create();

$feedUser = new FeedUser();
$feedUser->url = $url;
$feedUser->username = $user->username;

if ($feedUser->exists())
  exitError(409, "The feed already exists in the user's list.");

$feedUser->create();

http_response_code(201);
$res = null;
echo(json_encode($res));