<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/FeedUser.php");
require_once("config/feeds-util.php");

header("Access-Control-Allow-Methods: GET");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "GET")
  exitError(405, "Only GET requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

// Code
$feeds = FeedUser::findByUsername($user->username);

$feedsContent = [];
foreach ($feeds as $feed) {
  $doc = new DOMDocument();
  $doc->load($feed["url"]);
  if ($doc) {
    if ($doc->firstChild->nodeName === "rss")
      handleRss($doc, $feedsContent);
    else if ($doc->firstChild->nodeName === "feed")
      handleAtom($doc, $feedsContent);
  }
}

$res = ["feeds" => $feedsContent];
http_response_code(200);
echo(json_encode($res));